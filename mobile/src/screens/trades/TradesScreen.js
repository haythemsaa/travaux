import React, {useState, useEffect} from 'react';
import {
  View,
  StyleSheet,
  FlatList,
  RefreshControl,
  TouchableOpacity,
} from 'react-native';
import {
  Text,
  Card,
  Searchbar,
  Chip,
  ActivityIndicator,
  Surface,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';

export default function TradesScreen({navigation}) {
  const [trades, setTrades] = useState([]);
  const [filteredTrades, setFilteredTrades] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('all');

  const categories = [
    {key: 'all', label: 'Tous'},
    {key: 'construction', label: 'Construction'},
    {key: 'renovation', label: 'Rénovation'},
    {key: 'electricite', label: 'Électricité'},
    {key: 'plomberie', label: 'Plomberie'},
    {key: 'peinture', label: 'Peinture'},
    {key: 'menuiserie', label: 'Menuiserie'},
  ];

  useEffect(() => {
    loadTrades();
  }, []);

  useEffect(() => {
    filterTrades();
  }, [searchQuery, selectedCategory, trades]);

  const loadTrades = async () => {
    try {
      const response = await apiService.getTrades();
      if (response.success) {
        setTrades(response.data.trades || []);
      }
    } catch (error) {
      console.error('Error loading trades:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const filterTrades = () => {
    let filtered = trades;

    // Filter by search query
    if (searchQuery) {
      filtered = filtered.filter(
        trade =>
          trade.name_fr?.toLowerCase().includes(searchQuery.toLowerCase()) ||
          trade.name_en?.toLowerCase().includes(searchQuery.toLowerCase()),
      );
    }

    // Filter by category
    if (selectedCategory !== 'all') {
      filtered = filtered.filter(
        trade => trade.category?.toLowerCase() === selectedCategory,
      );
    }

    setFilteredTrades(filtered);
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadTrades();
  };

  const getIconName = slug => {
    const iconMap = {
      construction: 'home-city',
      electricite: 'lightning-bolt',
      plomberie: 'pipe-wrench',
      chauffage: 'radiator',
      climatisation: 'air-conditioner',
      plomberie: 'water-pump',
      peinture: 'format-paint',
      menuiserie: 'saw-blade',
      carrelage: 'grid',
      terrassement: 'excavator',
      roofing: 'home-roof',
    };
    return iconMap[slug] || 'wrench';
  };

  const renderTradeItem = ({item}) => (
    <TouchableOpacity
      onPress={() => navigation.navigate('TradeDetail', {trade: item})}>
      <Card style={styles.card}>
        <Card.Content>
          <View style={styles.cardHeader}>
            <View style={styles.iconContainer}>
              <Icon name={getIconName(item.slug)} size={32} color="#3b82f6" />
            </View>
            <View style={styles.cardInfo}>
              <Text style={styles.tradeName}>{item.name_fr}</Text>
              {item.name_en && (
                <Text style={styles.tradeNameEn}>{item.name_en}</Text>
              )}
            </View>
            <Icon name="chevron-right" size={24} color="#6b7280" />
          </View>

          {item.custom_fields_count > 0 && (
            <View style={styles.chipContainer}>
              <Chip
                icon="form-select"
                style={styles.chip}
                textStyle={styles.chipText}>
                {item.custom_fields_count} questions
              </Chip>
            </View>
          )}
        </Card.Content>
      </Card>
    </TouchableOpacity>
  );

  const renderHeader = () => (
    <View style={styles.header}>
      <Text style={styles.title}>Métiers & Services</Text>
      <Text style={styles.subtitle}>
        Découvrez tous les métiers disponibles
      </Text>

      <Searchbar
        placeholder="Rechercher un métier..."
        onChangeText={setSearchQuery}
        value={searchQuery}
        style={styles.searchbar}
      />

      <FlatList
        horizontal
        showsHorizontalScrollIndicator={false}
        data={categories}
        keyExtractor={item => item.key}
        renderItem={({item}) => (
          <Chip
            selected={selectedCategory === item.key}
            onPress={() => setSelectedCategory(item.key)}
            style={[
              styles.categoryChip,
              selectedCategory === item.key && styles.categoryChipSelected,
            ]}
            textStyle={[
              styles.categoryChipText,
              selectedCategory === item.key && styles.categoryChipTextSelected,
            ]}>
            {item.label}
          </Chip>
        )}
        style={styles.categoryList}
        contentContainerStyle={styles.categoryListContent}
      />

      <Surface style={styles.statsContainer}>
        <View style={styles.stat}>
          <Text style={styles.statNumber}>{trades.length}</Text>
          <Text style={styles.statLabel}>Métiers</Text>
        </View>
        <View style={styles.statDivider} />
        <View style={styles.stat}>
          <Text style={styles.statNumber}>
            {trades.reduce(
              (sum, trade) => sum + (trade.custom_fields_count || 0),
              0,
            )}
          </Text>
          <Text style={styles.statLabel}>Questions</Text>
        </View>
        <View style={styles.statDivider} />
        <View style={styles.stat}>
          <Text style={styles.statNumber}>5</Text>
          <Text style={styles.statLabel}>Langues</Text>
        </View>
      </Surface>

      <Text style={styles.resultsCount}>
        {filteredTrades.length} métier{filteredTrades.length > 1 ? 's' : ''}
      </Text>
    </View>
  );

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#3b82f6" />
        <Text style={styles.loadingText}>Chargement des métiers...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        ListHeaderComponent={renderHeader}
        data={filteredTrades}
        renderItem={renderTradeItem}
        keyExtractor={item => item.id.toString()}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="alert-circle-outline" size={64} color="#9ca3af" />
            <Text style={styles.emptyText}>Aucun métier trouvé</Text>
            <Text style={styles.emptySubtext}>
              Essayez de modifier votre recherche
            </Text>
          </View>
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
  centered: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f3f4f6',
  },
  loadingText: {
    marginTop: 16,
    fontSize: 16,
    color: '#6b7280',
  },
  header: {
    backgroundColor: '#fff',
    paddingTop: 16,
    paddingHorizontal: 16,
    paddingBottom: 8,
    borderBottomWidth: 1,
    borderBottomColor: '#e5e7eb',
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 4,
  },
  subtitle: {
    fontSize: 16,
    color: '#6b7280',
    marginBottom: 16,
  },
  searchbar: {
    marginBottom: 16,
    elevation: 0,
    backgroundColor: '#f3f4f6',
  },
  categoryList: {
    marginBottom: 16,
  },
  categoryListContent: {
    paddingRight: 16,
  },
  categoryChip: {
    marginRight: 8,
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  categoryChipSelected: {
    backgroundColor: '#3b82f6',
    borderColor: '#3b82f6',
  },
  categoryChipText: {
    color: '#374151',
  },
  categoryChipTextSelected: {
    color: '#fff',
  },
  statsContainer: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    padding: 16,
    borderRadius: 12,
    backgroundColor: '#fff',
    marginBottom: 16,
    elevation: 2,
  },
  stat: {
    alignItems: 'center',
    flex: 1,
  },
  statNumber: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#3b82f6',
    marginBottom: 4,
  },
  statLabel: {
    fontSize: 12,
    color: '#6b7280',
  },
  statDivider: {
    width: 1,
    backgroundColor: '#e5e7eb',
  },
  resultsCount: {
    fontSize: 14,
    fontWeight: '600',
    color: '#6b7280',
    marginBottom: 8,
  },
  listContent: {
    paddingBottom: 16,
  },
  card: {
    marginHorizontal: 16,
    marginVertical: 6,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  iconContainer: {
    width: 56,
    height: 56,
    borderRadius: 28,
    backgroundColor: '#eff6ff',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  cardInfo: {
    flex: 1,
  },
  tradeName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 2,
  },
  tradeNameEn: {
    fontSize: 13,
    color: '#6b7280',
  },
  chipContainer: {
    marginTop: 12,
    flexDirection: 'row',
  },
  chip: {
    backgroundColor: '#eff6ff',
  },
  chipText: {
    color: '#3b82f6',
    fontSize: 12,
  },
  emptyContainer: {
    alignItems: 'center',
    paddingVertical: 48,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: '600',
    color: '#374151',
    marginTop: 16,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#6b7280',
    marginTop: 4,
  },
});
