import React, {useEffect, useState} from 'react';
import {
  View,
  StyleSheet,
  FlatList,
  TouchableOpacity,
  RefreshControl,
} from 'react-native';
import {
  Title,
  Text,
  Card,
  FAB,
  Chip,
  Searchbar,
  ActivityIndicator,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import {useAuth} from '../../context/AuthContext';
import {apiService} from '../../services/api';

export default function ProjectsScreen({navigation}) {
  const {user} = useAuth();
  const [projects, setProjects] = useState([]);
  const [filteredProjects, setFilteredProjects] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedStatus, setSelectedStatus] = useState('all');

  useEffect(() => {
    loadProjects();
  }, []);

  useEffect(() => {
    filterProjects();
  }, [projects, searchQuery, selectedStatus]);

  const loadProjects = async () => {
    try {
      const response = await apiService.getProjects();
      if (response.success) {
        setProjects(response.data.projects || []);
      }
    } catch (error) {
      console.error('Error loading projects:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const filterProjects = () => {
    let filtered = projects;

    // Filter by status
    if (selectedStatus !== 'all') {
      filtered = filtered.filter((p) => p.status === selectedStatus);
    }

    // Filter by search query
    if (searchQuery) {
      filtered = filtered.filter(
        (p) =>
          p.title.toLowerCase().includes(searchQuery.toLowerCase()) ||
          p.description.toLowerCase().includes(searchQuery.toLowerCase())
      );
    }

    setFilteredProjects(filtered);
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadProjects();
  };

  const renderProjectItem = ({item}) => (
    <TouchableOpacity
      onPress={() => navigation.navigate('ProjectDetail', {id: item.id})}>
      <Card style={styles.card}>
        <Card.Content>
          <View style={styles.cardHeader}>
            <View style={styles.projectIconContainer}>
              <Icon name="folder" size={24} color="#3b82f6" />
            </View>
            <View style={styles.projectHeaderInfo}>
              <Title style={styles.projectTitle}>{item.title}</Title>
              <Text style={styles.projectLocation}>
                {item.city}, {item.postal_code}
              </Text>
            </View>
            <Chip
              style={[
                styles.statusChip,
                {backgroundColor: getStatusColor(item.status)},
              ]}
              textStyle={styles.statusText}>
              {getStatusLabel(item.status)}
            </Chip>
          </View>

          <Text style={styles.projectDescription} numberOfLines={2}>
            {item.description}
          </Text>

          <View style={styles.projectFooter}>
            <View style={styles.budgetContainer}>
              <Icon name="currency-eur" size={16} color="#6b7280" />
              <Text style={styles.budget}>
                {item.budget_min && item.budget_max
                  ? `${item.budget_min} - ${item.budget_max} €`
                  : item.budget_max
                  ? `Max ${item.budget_max} €`
                  : 'Budget non spécifié'}
              </Text>
            </View>
            <Text style={styles.date}>
              {new Date(item.created_at).toLocaleDateString('fr-FR')}
            </Text>
          </View>

          {item.quotes_count > 0 && user?.role === 'client' && (
            <View style={styles.quotesInfo}>
              <Icon name="file-document" size={16} color="#10b981" />
              <Text style={styles.quotesCount}>
                {item.quotes_count} devis reçu{item.quotes_count > 1 ? 's' : ''}
              </Text>
            </View>
          )}
        </Card.Content>
      </Card>
    </TouchableOpacity>
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
      {/* Search Bar */}
      <Searchbar
        placeholder="Rechercher un projet..."
        onChangeText={setSearchQuery}
        value={searchQuery}
        style={styles.searchBar}
      />

      {/* Status Filters */}
      <View style={styles.filterContainer}>
        <Chip
          selected={selectedStatus === 'all'}
          onPress={() => setSelectedStatus('all')}
          style={styles.filterChip}>
          Tous
        </Chip>
        <Chip
          selected={selectedStatus === 'open'}
          onPress={() => setSelectedStatus('open')}
          style={styles.filterChip}>
          Ouverts
        </Chip>
        <Chip
          selected={selectedStatus === 'in_progress'}
          onPress={() => setSelectedStatus('in_progress')}
          style={styles.filterChip}>
          En cours
        </Chip>
        <Chip
          selected={selectedStatus === 'completed'}
          onPress={() => setSelectedStatus('completed')}
          style={styles.filterChip}>
          Terminés
        </Chip>
      </View>

      {/* Projects List */}
      <FlatList
        data={filteredProjects}
        renderItem={renderProjectItem}
        keyExtractor={(item) => item.id.toString()}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="folder-outline" size={64} color="#d1d5db" />
            <Text style={styles.emptyText}>Aucun projet trouvé</Text>
            {user?.role === 'client' && (
              <Text style={styles.emptySubtext}>
                Créez votre premier projet pour commencer
              </Text>
            )}
          </View>
        }
      />

      {/* Floating Action Button (Client only) */}
      {user?.role === 'client' && (
        <FAB
          style={styles.fab}
          icon="plus"
          label="Nouveau projet"
          onPress={() => navigation.navigate('CreateProject')}
        />
      )}
    </View>
  );
}

function getStatusColor(status) {
  switch (status) {
    case 'open':
      return '#3b82f6';
    case 'in_progress':
      return '#f59e0b';
    case 'completed':
      return '#10b981';
    case 'cancelled':
      return '#ef4444';
    default:
      return '#6b7280';
  }
}

function getStatusLabel(status) {
  const labels = {
    open: 'Ouvert',
    in_progress: 'En cours',
    completed: 'Terminé',
    cancelled: 'Annulé',
  };
  return labels[status] || status;
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  searchBar: {
    margin: 15,
    marginBottom: 10,
  },
  filterContainer: {
    flexDirection: 'row',
    paddingHorizontal: 15,
    paddingBottom: 10,
  },
  filterChip: {
    marginRight: 8,
  },
  listContent: {
    padding: 15,
    paddingTop: 5,
  },
  card: {
    marginBottom: 15,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    marginBottom: 12,
  },
  projectIconContainer: {
    marginRight: 12,
  },
  projectHeaderInfo: {
    flex: 1,
  },
  projectTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 4,
  },
  projectLocation: {
    fontSize: 14,
    color: '#6b7280',
  },
  statusChip: {
    height: 28,
  },
  statusText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: 'bold',
  },
  projectDescription: {
    fontSize: 14,
    color: '#374151',
    marginBottom: 12,
    lineHeight: 20,
  },
  projectFooter: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  budgetContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  budget: {
    fontSize: 14,
    color: '#6b7280',
    marginLeft: 4,
    fontWeight: '500',
  },
  date: {
    fontSize: 12,
    color: '#9ca3af',
  },
  quotesInfo: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
    paddingTop: 8,
    borderTopWidth: 1,
    borderTopColor: '#f3f4f6',
  },
  quotesCount: {
    fontSize: 14,
    color: '#10b981',
    marginLeft: 6,
    fontWeight: '500',
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 60,
  },
  emptyText: {
    fontSize: 18,
    color: '#6b7280',
    marginTop: 16,
    fontWeight: '500',
  },
  emptySubtext: {
    fontSize: 14,
    color: '#9ca3af',
    marginTop: 8,
  },
  fab: {
    position: 'absolute',
    margin: 16,
    right: 0,
    bottom: 0,
    backgroundColor: '#3b82f6',
  },
});
