import React, {useState, useEffect, useCallback} from 'react';
import {
  View,
  StyleSheet,
  FlatList,
  RefreshControl,
  TouchableOpacity,
} from 'react-native';
import {
  Text,
  Surface,
  Chip,
  ActivityIndicator,
  SegmentedButtons,
  Searchbar,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

export default function QuotesScreen({navigation}) {
  const {user} = useAuth();
  const [quotes, setQuotes] = useState([]);
  const [filteredQuotes, setFilteredQuotes] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [selectedStatus, setSelectedStatus] = useState('all');
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    loadQuotes();
  }, []);

  useEffect(() => {
    filterQuotes();
  }, [selectedStatus, searchQuery, quotes]);

  const loadQuotes = async () => {
    try {
      const response = await apiService.getQuotes();
      if (response.success) {
        setQuotes(response.data.quotes);
      }
    } catch (error) {
      console.error('Error loading quotes:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadQuotes();
  }, []);

  const filterQuotes = () => {
    let filtered = quotes;

    // Filter by status
    if (selectedStatus !== 'all') {
      filtered = filtered.filter(q => q.status === selectedStatus);
    }

    // Filter by search query
    if (searchQuery.trim()) {
      filtered = filtered.filter(
        q =>
          q.project_title?.toLowerCase().includes(searchQuery.toLowerCase()) ||
          (user.role === 'client' &&
            q.artisan_name?.toLowerCase().includes(searchQuery.toLowerCase())),
      );
    }

    setFilteredQuotes(filtered);
  };

  const getStatusColor = status => {
    const colors = {
      pending: '#f59e0b',
      accepted: '#10b981',
      rejected: '#ef4444',
      expired: '#6b7280',
    };
    return colors[status] || '#6b7280';
  };

  const getStatusLabel = status => {
    const labels = {
      pending: 'En attente',
      accepted: 'Accepté',
      rejected: 'Refusé',
      expired: 'Expiré',
    };
    return labels[status] || status;
  };

  const formatCurrency = (amount, currency) => {
    const symbols = {
      EUR: '€',
      USD: '$',
      GBP: '£',
      CAD: 'CA$',
    };
    return `${amount.toLocaleString('fr-FR')} ${symbols[currency] || currency}`;
  };

  const formatDate = dateString => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: 'short',
      year: 'numeric',
    });
  };

  const renderQuote = ({item}) => (
    <TouchableOpacity
      onPress={() => navigation.navigate('QuoteDetail', {quoteId: item.id})}>
      <Surface style={styles.quoteCard}>
        <View style={styles.quoteHeader}>
          <View style={styles.quoteHeaderLeft}>
            <Text style={styles.projectTitle} numberOfLines={1}>
              {item.project_title}
            </Text>
            {user.role === 'client' && (
              <Text style={styles.artisanName}>{item.artisan_name}</Text>
            )}
          </View>
          <Chip
            style={[
              styles.statusChip,
              {backgroundColor: getStatusColor(item.status) + '20'},
            ]}
            textStyle={{color: getStatusColor(item.status), fontSize: 12}}>
            {getStatusLabel(item.status)}
          </Chip>
        </View>

        <View style={styles.quoteBody}>
          <View style={styles.quoteRow}>
            <Icon name="cash" size={18} color="#6b7280" />
            <Text style={styles.amount}>
              {formatCurrency(item.amount, item.currency_code)}
            </Text>
          </View>

          {item.estimated_duration && (
            <View style={styles.quoteRow}>
              <Icon name="clock-outline" size={18} color="#6b7280" />
              <Text style={styles.quoteDetail}>{item.estimated_duration}</Text>
            </View>
          )}

          {item.start_date && (
            <View style={styles.quoteRow}>
              <Icon name="calendar" size={18} color="#6b7280" />
              <Text style={styles.quoteDetail}>
                Début: {formatDate(item.start_date)}
              </Text>
            </View>
          )}

          {item.valid_until && item.status === 'pending' && (
            <View style={styles.quoteRow}>
              <Icon name="timer-sand" size={18} color="#f59e0b" />
              <Text style={[styles.quoteDetail, {color: '#f59e0b'}]}>
                Valide jusqu'au {formatDate(item.valid_until)}
              </Text>
            </View>
          )}
        </View>

        <View style={styles.quoteFooter}>
          <Text style={styles.quoteDate}>
            {formatDate(item.created_at)}
          </Text>
          {item.status === 'pending' && user.role === 'client' && (
            <View style={styles.actions}>
              <Text style={styles.actionText}>Voir les détails →</Text>
            </View>
          )}
        </View>
      </Surface>
    </TouchableOpacity>
  );

  const renderEmpty = () => (
    <View style={styles.emptyContainer}>
      <Icon name="file-document-outline" size={64} color="#d1d5db" />
      <Text style={styles.emptyText}>
        {selectedStatus === 'all'
          ? 'Aucun devis'
          : `Aucun devis ${getStatusLabel(selectedStatus).toLowerCase()}`}
      </Text>
      <Text style={styles.emptySubtext}>
        {user.role === 'artisan'
          ? 'Consultez les projets pour envoyer des devis'
          : 'Les devis reçus apparaîtront ici'}
      </Text>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Searchbar
        placeholder="Rechercher un devis..."
        onChangeText={setSearchQuery}
        value={searchQuery}
        style={styles.searchBar}
      />

      <SegmentedButtons
        value={selectedStatus}
        onValueChange={setSelectedStatus}
        buttons={[
          {value: 'all', label: 'Tous'},
          {value: 'pending', label: 'En attente'},
          {value: 'accepted', label: 'Acceptés'},
          {value: 'rejected', label: 'Refusés'},
        ]}
        style={styles.segmentedButtons}
      />

      <FlatList
        data={filteredQuotes}
        renderItem={renderQuote}
        keyExtractor={item => item.id.toString()}
        contentContainerStyle={styles.listContainer}
        ListEmptyComponent={renderEmpty}
        refreshControl={
          <RefreshControl
            refreshing={refreshing}
            onRefresh={onRefresh}
            colors={['#3b82f6']}
          />
        }
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  searchBar: {
    margin: 15,
    elevation: 2,
  },
  segmentedButtons: {
    marginHorizontal: 15,
    marginBottom: 15,
  },
  listContainer: {
    padding: 15,
    paddingTop: 0,
  },
  quoteCard: {
    padding: 15,
    marginBottom: 12,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  quoteHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 12,
  },
  quoteHeaderLeft: {
    flex: 1,
    marginRight: 10,
  },
  projectTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 4,
  },
  artisanName: {
    fontSize: 14,
    color: '#6b7280',
  },
  statusChip: {
    height: 28,
  },
  quoteBody: {
    marginBottom: 12,
  },
  quoteRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  amount: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#10b981',
    marginLeft: 8,
  },
  quoteDetail: {
    fontSize: 14,
    color: '#6b7280',
    marginLeft: 8,
  },
  quoteFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingTop: 12,
    borderTopWidth: 1,
    borderTopColor: '#f3f4f6',
  },
  quoteDate: {
    fontSize: 12,
    color: '#9ca3af',
  },
  actions: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  actionText: {
    fontSize: 14,
    color: '#3b82f6',
    fontWeight: '600',
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 60,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#6b7280',
    marginTop: 16,
    marginBottom: 8,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#9ca3af',
    textAlign: 'center',
  },
});
