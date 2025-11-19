import React, {useState, useEffect, useCallback} from 'react';
import {
  View,
  StyleSheet,
  FlatList,
  RefreshControl,
  TouchableOpacity,
  Alert,
} from 'react-native';
import {
  Text,
  Card,
  ActivityIndicator,
  IconButton,
  Chip,
  Avatar,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';

export default function FavoritesScreen({navigation}) {
  const [favorites, setFavorites] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadFavorites();
  }, []);

  const loadFavorites = async () => {
    try {
      const response = await apiService.getFavorites();
      if (response.success) {
        setFavorites(response.data.favorites || []);
      }
    } catch (error) {
      console.error('Error loading favorites:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadFavorites();
  }, []);

  const handleRemoveFavorite = async artisanId => {
    Alert.alert(
      'Retirer des favoris',
      'Voulez-vous retirer cet artisan de vos favoris ?',
      [
        {text: 'Annuler', style: 'cancel'},
        {
          text: 'Retirer',
          style: 'destructive',
          onPress: async () => {
            try {
              await apiService.toggleFavorite(artisanId);
              setFavorites(favorites.filter(f => f.id !== artisanId));
            } catch (error) {
              Alert.alert('Erreur', 'Impossible de retirer cet artisan');
            }
          },
        },
      ],
    );
  };

  const renderFavoriteItem = ({item}) => (
    <Card style={styles.card}>
      <TouchableOpacity
        onPress={() => navigation.navigate('ArtisanDetail', {id: item.id})}>
        <Card.Content>
          <View style={styles.cardHeader}>
            {item.profile_image ? (
              <Avatar.Image
                size={56}
                source={{uri: item.profile_image}}
                style={styles.avatar}
              />
            ) : (
              <Avatar.Icon
                size={56}
                icon="account"
                style={styles.avatar}
                color="#3b82f6"
                backgroundColor="#eff6ff"
              />
            )}
            <View style={styles.artisanInfo}>
              <Text style={styles.artisanName}>{item.company_name || item.name}</Text>
              {item.category && (
                <Text style={styles.category}>{item.category}</Text>
              )}
              {item.city && (
                <View style={styles.locationRow}>
                  <Icon name="map-marker" size={14} color="#6b7280" />
                  <Text style={styles.location}>{item.city}</Text>
                </View>
              )}
            </View>
            <IconButton
              icon="heart"
              iconColor="#ef4444"
              size={24}
              onPress={() => handleRemoveFavorite(item.id)}
            />
          </View>

          {item.rating && (
            <View style={styles.ratingRow}>
              <Icon name="star" size={16} color="#fbbf24" />
              <Text style={styles.ratingText}>{item.rating.toFixed(1)}</Text>
              <Text style={styles.reviewsCount}>
                ({item.reviews_count} avis)
              </Text>
            </View>
          )}

          {item.badges && item.badges.length > 0 && (
            <View style={styles.badgesRow}>
              {item.badges.slice(0, 3).map((badge, index) => (
                <Chip
                  key={index}
                  mode="outlined"
                  style={styles.badge}
                  textStyle={styles.badgeText}>
                  {badge}
                </Chip>
              ))}
            </View>
          )}
        </Card.Content>
      </TouchableOpacity>
    </Card>
  );

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#3b82f6" />
        <Text style={styles.loadingText}>Chargement de vos favoris...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={favorites}
        renderItem={renderFavoriteItem}
        keyExtractor={item => item.id.toString()}
        contentContainerStyle={styles.listContent}
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }
        ListEmptyComponent={
          <View style={styles.emptyContainer}>
            <Icon name="heart-outline" size={80} color="#d1d5db" />
            <Text style={styles.emptyTitle}>Aucun favori</Text>
            <Text style={styles.emptyText}>
              Ajoutez des artisans à vos favoris pour les retrouver facilement
            </Text>
            <TouchableOpacity
              style={styles.browseButton}
              onPress={() => navigation.navigate('Artisans')}>
              <Text style={styles.browseButtonText}>Parcourir les artisans</Text>
              <Icon name="chevron-right" size={20} color="#fff" />
            </TouchableOpacity>
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
  listContent: {
    padding: 16,
    paddingBottom: 32,
  },
  card: {
    marginBottom: 12,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  avatar: {
    marginRight: 12,
  },
  artisanInfo: {
    flex: 1,
  },
  artisanName: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 4,
  },
  category: {
    fontSize: 13,
    color: '#6b7280',
    marginBottom: 4,
  },
  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 4,
  },
  location: {
    fontSize: 12,
    color: '#6b7280',
  },
  ratingRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 12,
    gap: 6,
  },
  ratingText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#111827',
  },
  reviewsCount: {
    fontSize: 13,
    color: '#6b7280',
  },
  badgesRow: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    marginTop: 12,
    gap: 6,
  },
  badge: {
    height: 26,
    backgroundColor: '#eff6ff',
    borderColor: '#bfdbfe',
  },
  badgeText: {
    fontSize: 11,
    color: '#3b82f6',
  },
  emptyContainer: {
    alignItems: 'center',
    paddingVertical: 64,
    paddingHorizontal: 32,
  },
  emptyTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#111827',
    marginTop: 24,
    marginBottom: 8,
  },
  emptyText: {
    fontSize: 15,
    color: '#6b7280',
    textAlign: 'center',
    marginBottom: 24,
    lineHeight: 22,
  },
  browseButton: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: '#3b82f6',
    paddingHorizontal: 24,
    paddingVertical: 12,
    borderRadius: 12,
    gap: 8,
  },
  browseButtonText: {
    color: '#fff',
    fontSize: 16,
    fontWeight: '600',
  },
});
