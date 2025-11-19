import React, {useState, useEffect} from 'react';
import {
  View,
  StyleSheet,
  ScrollView,
  RefreshControl,
  Dimensions,
} from 'react-native';
import {
  Text,
  Card,
  ActivityIndicator,
  Searchbar,
  Chip,
  Surface,
  Divider,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';

const {width} = Dimensions.get('window');

export default function MarketPricesScreen() {
  const [prices, setPrices] = useState([]);
  const [filteredPrices, setFilteredPrices] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedRegion, setSelectedRegion] = useState('all');

  const regions = [
    {key: 'all', label: 'Toutes régions'},
    {key: 'ile-de-france', label: 'Île-de-France'},
    {key: 'provence', label: 'Provence'},
    {key: 'bretagne', label: 'Bretagne'},
    {key: 'occitanie', label: 'Occitanie'},
  ];

  useEffect(() => {
    loadMarketPrices();
  }, []);

  useEffect(() => {
    filterPrices();
  }, [searchQuery, selectedRegion, prices]);

  const loadMarketPrices = async () => {
    try {
      const response = await apiService.getMarketPrices();
      if (response.success) {
        setPrices(response.data.prices || []);
      }
    } catch (error) {
      console.error('Error loading market prices:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const filterPrices = () => {
    let filtered = prices;

    if (searchQuery) {
      filtered = filtered.filter(price =>
        price.category?.toLowerCase().includes(searchQuery.toLowerCase()),
      );
    }

    if (selectedRegion !== 'all') {
      filtered = filtered.filter(
        price => price.region?.toLowerCase() === selectedRegion,
      );
    }

    setFilteredPrices(filtered);
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadMarketPrices();
  };

  const formatPrice = price => {
    return new Intl.NumberFormat('fr-FR', {
      style: 'currency',
      currency: 'EUR',
      minimumFractionDigits: 0,
      maximumFractionDigits: 0,
    }).format(price);
  };

  const renderPriceCard = price => (
    <Card key={price.id} style={styles.card}>
      <Card.Content>
        <View style={styles.cardHeader}>
          <View style={styles.iconContainer}>
            <Icon name="currency-eur" size={28} color="#3b82f6" />
          </View>
          <View style={styles.cardInfo}>
            <Text style={styles.categoryName}>{price.category}</Text>
            {price.region && (
              <View style={styles.regionRow}>
                <Icon name="map-marker" size={14} color="#6b7280" />
                <Text style={styles.regionText}>{price.region}</Text>
              </View>
            )}
          </View>
        </View>

        <Divider style={styles.divider} />

        <View style={styles.priceRow}>
          <View style={styles.priceItem}>
            <Text style={styles.priceLabel}>Min</Text>
            <Text style={styles.priceValue}>
              {formatPrice(price.min_price)}
            </Text>
          </View>

          <View style={styles.priceDivider} />

          <View style={[styles.priceItem, styles.averagePrice]}>
            <Text style={styles.priceLabelAverage}>Moyenne</Text>
            <Text style={styles.priceValueAverage}>
              {formatPrice(price.avg_price)}
            </Text>
          </View>

          <View style={styles.priceDivider} />

          <View style={styles.priceItem}>
            <Text style={styles.priceLabel}>Max</Text>
            <Text style={styles.priceValue}>
              {formatPrice(price.max_price)}
            </Text>
          </View>
        </View>

        {price.unit && (
          <Text style={styles.unitText}>Par {price.unit}</Text>
        )}

        {price.sample_size && (
          <Surface style={styles.sampleBadge}>
            <Icon name="chart-bar" size={14} color="#6b7280" />
            <Text style={styles.sampleText}>
              Basé sur {price.sample_size} devis
            </Text>
          </Surface>
        )}
      </Card.Content>
    </Card>
  );

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#3b82f6" />
        <Text style={styles.loadingText}>
          Chargement des prix du marché...
        </Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <ScrollView
        contentContainerStyle={styles.scrollContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }>
        {/* Header */}
        <View style={styles.header}>
          <Text style={styles.title}>Prix du marché</Text>
          <Text style={styles.subtitle}>
            Comparez les prix moyens par catégorie et région
          </Text>

          <Searchbar
            placeholder="Rechercher une catégorie..."
            onChangeText={setSearchQuery}
            value={searchQuery}
            style={styles.searchbar}
          />

          <ScrollView
            horizontal
            showsHorizontalScrollIndicator={false}
            style={styles.regionsScroll}
            contentContainerStyle={styles.regionsContent}>
            {regions.map(region => (
              <Chip
                key={region.key}
                selected={selectedRegion === region.key}
                onPress={() => setSelectedRegion(region.key)}
                style={[
                  styles.regionChip,
                  selectedRegion === region.key && styles.regionChipSelected,
                ]}
                textStyle={[
                  styles.regionChipText,
                  selectedRegion === region.key &&
                    styles.regionChipTextSelected,
                ]}>
                {region.label}
              </Chip>
            ))}
          </ScrollView>
        </View>

        {/* Info Card */}
        <Card style={styles.infoCard}>
          <Card.Content>
            <View style={styles.infoHeader}>
              <Icon name="information" size={24} color="#3b82f6" />
              <Text style={styles.infoTitle}>À propos des prix</Text>
            </View>
            <Text style={styles.infoText}>
              Les prix affichés sont des moyennes calculées à partir de devis
              réels. Ils sont donnés à titre indicatif et peuvent varier selon
              la complexité du projet, les matériaux choisis et la région.
            </Text>
          </Card.Content>
        </Card>

        {/* Results */}
        <View style={styles.resultsHeader}>
          <Text style={styles.resultsCount}>
            {filteredPrices.length} catégorie
            {filteredPrices.length > 1 ? 's' : ''}
          </Text>
        </View>

        {filteredPrices.length > 0 ? (
          filteredPrices.map(renderPriceCard)
        ) : (
          <View style={styles.emptyContainer}>
            <Icon name="alert-circle-outline" size={64} color="#9ca3af" />
            <Text style={styles.emptyText}>Aucun résultat</Text>
            <Text style={styles.emptySubtext}>
              Essayez de modifier votre recherche ou vos filtres
            </Text>
          </View>
        )}
      </ScrollView>
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
  scrollContent: {
    paddingBottom: 32,
  },
  header: {
    backgroundColor: '#fff',
    paddingTop: 16,
    paddingHorizontal: 16,
    paddingBottom: 16,
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
    fontSize: 15,
    color: '#6b7280',
    marginBottom: 16,
  },
  searchbar: {
    marginBottom: 16,
    elevation: 0,
    backgroundColor: '#f3f4f6',
  },
  regionsScroll: {
    marginHorizontal: -16,
    paddingHorizontal: 16,
  },
  regionsContent: {
    paddingRight: 16,
  },
  regionChip: {
    marginRight: 8,
    backgroundColor: '#fff',
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  regionChipSelected: {
    backgroundColor: '#3b82f6',
    borderColor: '#3b82f6',
  },
  regionChipText: {
    color: '#374151',
  },
  regionChipTextSelected: {
    color: '#fff',
  },
  infoCard: {
    marginHorizontal: 16,
    marginTop: 16,
    elevation: 1,
  },
  infoHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    marginBottom: 12,
  },
  infoTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
  },
  infoText: {
    fontSize: 14,
    color: '#4b5563',
    lineHeight: 20,
  },
  resultsHeader: {
    paddingHorizontal: 16,
    paddingTop: 16,
    paddingBottom: 8,
  },
  resultsCount: {
    fontSize: 14,
    fontWeight: '600',
    color: '#6b7280',
  },
  card: {
    marginHorizontal: 16,
    marginBottom: 12,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
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
  categoryName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 4,
  },
  regionRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  regionText: {
    fontSize: 13,
    color: '#6b7280',
  },
  divider: {
    marginBottom: 16,
  },
  priceRow: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginBottom: 12,
  },
  priceItem: {
    alignItems: 'center',
    flex: 1,
  },
  averagePrice: {
    backgroundColor: '#eff6ff',
    paddingVertical: 8,
    borderRadius: 8,
  },
  priceLabel: {
    fontSize: 12,
    color: '#6b7280',
    marginBottom: 4,
    fontWeight: '500',
  },
  priceLabelAverage: {
    fontSize: 12,
    color: '#3b82f6',
    marginBottom: 4,
    fontWeight: '600',
  },
  priceValue: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
  },
  priceValueAverage: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#3b82f6',
  },
  priceDivider: {
    width: 1,
    backgroundColor: '#e5e7eb',
  },
  unitText: {
    fontSize: 12,
    color: '#9ca3af',
    textAlign: 'center',
    marginBottom: 12,
  },
  sampleBadge: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    padding: 8,
    borderRadius: 6,
    backgroundColor: '#f9fafb',
    justifyContent: 'center',
  },
  sampleText: {
    fontSize: 11,
    color: '#6b7280',
  },
  emptyContainer: {
    alignItems: 'center',
    paddingVertical: 48,
    paddingHorizontal: 32,
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
    textAlign: 'center',
  },
});
