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
  Searchbar,
  Surface,
  Chip,
  ActivityIndicator,
  Avatar,
  Button,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';

export default function ArtisansScreen({navigation}) {
  const [artisans, setArtisans] = useState([]);
  const [filteredArtisans, setFilteredArtisans] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');
  const [selectedCategory, setSelectedCategory] = useState(null);
  const [categories, setCategories] = useState([]);

  useEffect(() => {
    loadCategories();
    loadArtisans();
  }, []);

  useEffect(() => {
    filterArtisans();
  }, [searchQuery, selectedCategory, artisans]);

  const loadCategories = async () => {
    try {
      const response = await apiService.getCategories();
      if (response.success) {
        setCategories(response.data.categories.slice(0, 10)); // Top 10
      }
    } catch (error) {
      console.error('Error loading categories:', error);
    }
  };

  const loadArtisans = async () => {
    try {
      const params = {};
      if (selectedCategory) {
        params.category_id = selectedCategory;
      }

      const response = await apiService.searchArtisans(params);
      if (response.success) {
        setArtisans(response.data.artisans);
      }
    } catch (error) {
      console.error('Error loading artisans:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadArtisans();
  }, [selectedCategory]);

  const filterArtisans = () => {
    let filtered = artisans;

    if (searchQuery.trim()) {
      filtered = filtered.filter(
        a =>
          a.first_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
          a.last_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
          a.company_name?.toLowerCase().includes(searchQuery.toLowerCase()) ||
          a.city?.toLowerCase().includes(searchQuery.toLowerCase()),
      );
    }

    setFilteredArtisans(filtered);
  };

  const handleCategoryPress = categoryId => {
    if (selectedCategory === categoryId) {
      setSelectedCategory(null);
    } else {
      setSelectedCategory(categoryId);
      setLoading(true);
      loadArtisans();
    }
  };

  const renderStars = rating => {
    const stars = [];
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;

    for (let i = 0; i < fullStars; i++) {
      stars.push(
        <Icon key={`full-${i}`} name="star" size={14} color="#f59e0b" />,
      );
    }

    if (hasHalfStar) {
      stars.push(
        <Icon key="half" name="star-half-full" size={14} color="#f59e0b" />,
      );
    }

    const emptyStars = 5 - stars.length;
    for (let i = 0; i < emptyStars; i++) {
      stars.push(
        <Icon
          key={`empty-${i}`}
          name="star-outline"
          size={14}
          color="#d1d5db"
        />,
      );
    }

    return <View style={styles.starsContainer}>{stars}</View>;
  };

  const getInitials = artisan => {
    const firstName = artisan.first_name || '';
    const lastName = artisan.last_name || '';
    if (firstName && lastName) {
      return (firstName[0] + lastName[0]).toUpperCase();
    }
    return artisan.company_name?.substring(0, 2).toUpperCase() || 'A';
  };

  const renderArtisan = ({item}) => (
    <TouchableOpacity
      onPress={() =>
        navigation.navigate('ArtisanDetail', {artisanId: item.id})
      }>
      <Surface style={styles.artisanCard}>
        <View style={styles.artisanHeader}>
          <Avatar.Text
            size={60}
            label={getInitials(item)}
            style={styles.avatar}
          />

          <View style={styles.artisanInfo}>
            <Text style={styles.artisanName} numberOfLines={1}>
              {item.first_name} {item.last_name}
            </Text>

            {item.company_name && (
              <Text style={styles.companyName} numberOfLines={1}>
                {item.company_name}
              </Text>
            )}

            <View style={styles.ratingRow}>
              {renderStars(item.rating_average || 0)}
              <Text style={styles.ratingText}>
                {(item.rating_average || 0).toFixed(1)} ({item.review_count || 0})
              </Text>
            </View>

            <View style={styles.locationRow}>
              <Icon name="map-marker" size={14} color="#6b7280" />
              <Text style={styles.locationText}>{item.city}</Text>
              {item.distance && (
                <Text style={styles.distanceText}>
                  • {item.distance.toFixed(1)} km
                </Text>
              )}
            </View>
          </View>
        </View>

        {item.specialties && item.specialties.length > 0 && (
          <View style={styles.specialtiesContainer}>
            {item.specialties.slice(0, 3).map((specialty, index) => (
              <Chip key={index} style={styles.specialtyChip} compact>
                {specialty}
              </Chip>
            ))}
            {item.specialties.length > 3 && (
              <Chip style={styles.specialtyChip} compact>
                +{item.specialties.length - 3}
              </Chip>
            )}
          </View>
        )}

        {item.experience_years && (
          <View style={styles.experienceRow}>
            <Icon name="medal" size={16} color="#f59e0b" />
            <Text style={styles.experienceText}>
              {item.experience_years} ans d'expérience
            </Text>
          </View>
        )}
      </Surface>
    </TouchableOpacity>
  );

  const renderEmpty = () => (
    <View style={styles.emptyContainer}>
      <Icon name="account-search" size={64} color="#d1d5db" />
      <Text style={styles.emptyText}>Aucun artisan trouvé</Text>
      <Text style={styles.emptySubtext}>
        Essayez de modifier vos critères de recherche
      </Text>
    </View>
  );

  if (loading && !refreshing) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <Searchbar
        placeholder="Rechercher un artisan..."
        onChangeText={setSearchQuery}
        value={searchQuery}
        style={styles.searchBar}
      />

      {/* Category Filters */}
      <View style={styles.categoriesContainer}>
        <FlatList
          horizontal
          showsHorizontalScrollIndicator={false}
          data={categories}
          keyExtractor={item => item.id.toString()}
          renderItem={({item}) => (
            <Chip
              selected={selectedCategory === item.id}
              onPress={() => handleCategoryPress(item.id)}
              style={styles.categoryChip}>
              {item.name}
            </Chip>
          )}
          contentContainerStyle={styles.categoriesList}
        />
      </View>

      <FlatList
        data={filteredArtisans}
        renderItem={renderArtisan}
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
  categoriesContainer: {
    marginBottom: 10,
  },
  categoriesList: {
    paddingHorizontal: 15,
  },
  categoryChip: {
    marginRight: 8,
  },
  listContainer: {
    padding: 15,
    paddingTop: 5,
  },
  artisanCard: {
    padding: 15,
    marginBottom: 12,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  artisanHeader: {
    flexDirection: 'row',
    marginBottom: 12,
  },
  avatar: {
    backgroundColor: '#3b82f6',
    marginRight: 12,
  },
  artisanInfo: {
    flex: 1,
  },
  artisanName: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 2,
  },
  companyName: {
    fontSize: 14,
    color: '#6b7280',
    marginBottom: 6,
  },
  ratingRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  starsContainer: {
    flexDirection: 'row',
    marginRight: 6,
  },
  ratingText: {
    fontSize: 13,
    color: '#6b7280',
  },
  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  locationText: {
    fontSize: 13,
    color: '#6b7280',
    marginLeft: 4,
  },
  distanceText: {
    fontSize: 13,
    color: '#9ca3af',
    marginLeft: 4,
  },
  specialtiesContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginBottom: 8,
  },
  specialtyChip: {
    marginRight: 6,
    marginBottom: 6,
    backgroundColor: '#eff6ff',
  },
  experienceRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  experienceText: {
    fontSize: 13,
    color: '#6b7280',
    marginLeft: 6,
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
