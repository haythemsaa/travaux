import React, {useState, useEffect} from 'react';
import {View, StyleSheet, ScrollView, Image, Dimensions} from 'react-native';
import {
  Text,
  Surface,
  Button,
  Chip,
  Divider,
  ActivityIndicator,
  Avatar,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

const {width} = Dimensions.get('window');

export default function ArtisanDetailScreen({route, navigation}) {
  const {artisanId} = route.params;
  const {user} = useAuth();
  const [artisan, setArtisan] = useState(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadArtisanDetails();
  }, [artisanId]);

  const loadArtisanDetails = async () => {
    try {
      const response = await apiService.getArtisanProfile(artisanId);
      if (response.success) {
        setArtisan(response.data.artisan);
      }
    } catch (error) {
      console.error('Error loading artisan details:', error);
    } finally {
      setLoading(false);
    }
  };

  const renderStars = rating => {
    const stars = [];
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;

    for (let i = 0; i < fullStars; i++) {
      stars.push(
        <Icon key={`full-${i}`} name="star" size={20} color="#f59e0b" />,
      );
    }

    if (hasHalfStar) {
      stars.push(
        <Icon key="half" name="star-half-full" size={20} color="#f59e0b" />,
      );
    }

    const emptyStars = 5 - stars.length;
    for (let i = 0; i < emptyStars; i++) {
      stars.push(
        <Icon
          key={`empty-${i}`}
          name="star-outline"
          size={20}
          color="#d1d5db"
        />,
      );
    }

    return <View style={styles.starsContainer}>{stars}</View>;
  };

  const getInitials = () => {
    if (!artisan) return 'A';
    const firstName = artisan.first_name || '';
    const lastName = artisan.last_name || '';
    if (firstName && lastName) {
      return (firstName[0] + lastName[0]).toUpperCase();
    }
    return artisan.company_name?.substring(0, 2).toUpperCase() || 'A';
  };

  const handleContact = () => {
    navigation.navigate('Chat', {
      userId: artisanId,
      userName: `${artisan.first_name} ${artisan.last_name}`,
    });
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  if (!artisan) {
    return (
      <View style={styles.errorContainer}>
        <Text>Artisan introuvable</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        {/* Header */}
        <Surface style={styles.header}>
          <Avatar.Text
            size={80}
            label={getInitials()}
            style={styles.avatar}
          />

          <Text style={styles.name}>
            {artisan.first_name} {artisan.last_name}
          </Text>

          {artisan.company_name && (
            <Text style={styles.companyName}>{artisan.company_name}</Text>
          )}

          <View style={styles.ratingContainer}>
            {renderStars(artisan.rating_average || 0)}
            <Text style={styles.ratingText}>
              {(artisan.rating_average || 0).toFixed(1)} •{' '}
              {artisan.review_count || 0} avis
            </Text>
          </View>

          {artisan.city && (
            <View style={styles.locationRow}>
              <Icon name="map-marker" size={18} color="#6b7280" />
              <Text style={styles.locationText}>{artisan.city}</Text>
            </View>
          )}
        </Surface>

        {/* Badges */}
        {artisan.badges && artisan.badges.length > 0 && (
          <Surface style={styles.section}>
            <Text style={styles.sectionTitle}>Certifications</Text>
            <View style={styles.badgesContainer}>
              {artisan.badges.map((badge, index) => (
                <View key={index} style={styles.badgeItem}>
                  <Icon
                    name={badge.icon || 'shield-check'}
                    size={24}
                    color={badge.color || '#10b981'}
                  />
                  <Text style={styles.badgeText}>{badge.name}</Text>
                </View>
              ))}
            </View>
          </Surface>
        )}

        {/* Specialties */}
        {artisan.specialties && artisan.specialties.length > 0 && (
          <Surface style={styles.section}>
            <Text style={styles.sectionTitle}>Spécialités</Text>
            <View style={styles.specialtiesContainer}>
              {artisan.specialties.map((specialty, index) => (
                <Chip key={index} style={styles.specialtyChip}>
                  {specialty}
                </Chip>
              ))}
            </View>
          </Surface>
        )}

        {/* Description */}
        {artisan.description && (
          <Surface style={styles.section}>
            <Text style={styles.sectionTitle}>À propos</Text>
            <Text style={styles.description}>{artisan.description}</Text>
          </Surface>
        )}

        {/* Experience */}
        {artisan.experience_years && (
          <Surface style={styles.section}>
            <View style={styles.infoRow}>
              <Icon name="medal" size={24} color="#f59e0b" />
              <View style={styles.infoContent}>
                <Text style={styles.infoLabel}>Expérience</Text>
                <Text style={styles.infoValue}>
                  {artisan.experience_years} ans
                </Text>
              </View>
            </View>
          </Surface>
        )}

        {/* Portfolio */}
        {artisan.portfolio && artisan.portfolio.length > 0 && (
          <Surface style={styles.section}>
            <Text style={styles.sectionTitle}>Réalisations</Text>
            <ScrollView
              horizontal
              showsHorizontalScrollIndicator={false}
              contentContainerStyle={styles.portfolioScroll}>
              {artisan.portfolio.map((item, index) => (
                <View key={index} style={styles.portfolioItem}>
                  <Image
                    source={{uri: item.image_url}}
                    style={styles.portfolioImage}
                  />
                  {item.title && (
                    <Text style={styles.portfolioTitle} numberOfLines={1}>
                      {item.title}
                    </Text>
                  )}
                </View>
              ))}
            </ScrollView>
          </Surface>
        )}

        {/* Reviews */}
        {artisan.recent_reviews && artisan.recent_reviews.length > 0 && (
          <Surface style={styles.section}>
            <Text style={styles.sectionTitle}>Avis récents</Text>
            {artisan.recent_reviews.map((review, index) => (
              <View key={index}>
                <View style={styles.reviewItem}>
                  <View style={styles.reviewHeader}>
                    <Text style={styles.reviewAuthor}>
                      {review.client_name}
                    </Text>
                    {renderStars(review.rating)}
                  </View>
                  {review.comment && (
                    <Text style={styles.reviewComment}>{review.comment}</Text>
                  )}
                  <Text style={styles.reviewDate}>
                    {new Date(review.created_at).toLocaleDateString('fr-FR')}
                  </Text>
                </View>
                {index < artisan.recent_reviews.length - 1 && <Divider />}
              </View>
            ))}
          </Surface>
        )}
      </ScrollView>

      {/* Contact Button */}
      {user?.role === 'client' && (
        <View style={styles.footer}>
          <Button
            mode="contained"
            onPress={handleContact}
            style={styles.contactButton}
            buttonColor="#3b82f6"
            icon="message">
            Contacter
          </Button>
        </View>
      )}
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
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  scrollContent: {
    paddingBottom: 20,
  },
  header: {
    alignItems: 'center',
    padding: 30,
    marginBottom: 15,
    elevation: 2,
    backgroundColor: '#fff',
  },
  avatar: {
    backgroundColor: '#3b82f6',
    marginBottom: 15,
  },
  name: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 4,
  },
  companyName: {
    fontSize: 16,
    color: '#6b7280',
    marginBottom: 12,
  },
  ratingContainer: {
    alignItems: 'center',
    marginBottom: 8,
  },
  starsContainer: {
    flexDirection: 'row',
    marginBottom: 4,
  },
  ratingText: {
    fontSize: 14,
    color: '#6b7280',
  },
  locationRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  locationText: {
    fontSize: 14,
    color: '#6b7280',
    marginLeft: 4,
  },
  section: {
    padding: 20,
    marginHorizontal: 15,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 15,
  },
  badgesContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
  },
  badgeItem: {
    flexDirection: 'row',
    alignItems: 'center',
    marginRight: 20,
    marginBottom: 10,
  },
  badgeText: {
    fontSize: 14,
    color: '#374151',
    marginLeft: 8,
  },
  specialtiesContainer: {
    flexDirection: 'row',
    flexWrap: 'wrap',
  },
  specialtyChip: {
    marginRight: 8,
    marginBottom: 8,
    backgroundColor: '#eff6ff',
  },
  description: {
    fontSize: 15,
    color: '#374151',
    lineHeight: 24,
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  infoContent: {
    marginLeft: 12,
  },
  infoLabel: {
    fontSize: 13,
    color: '#6b7280',
    marginBottom: 2,
  },
  infoValue: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
  },
  portfolioScroll: {
    paddingRight: 15,
  },
  portfolioItem: {
    marginRight: 12,
  },
  portfolioImage: {
    width: width * 0.6,
    height: 180,
    borderRadius: 12,
    backgroundColor: '#e5e7eb',
  },
  portfolioTitle: {
    fontSize: 13,
    color: '#374151',
    marginTop: 8,
    width: width * 0.6,
  },
  reviewItem: {
    paddingVertical: 12,
  },
  reviewHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  reviewAuthor: {
    fontSize: 14,
    fontWeight: '600',
    color: '#111827',
  },
  reviewComment: {
    fontSize: 14,
    color: '#374151',
    lineHeight: 20,
    marginBottom: 6,
  },
  reviewDate: {
    fontSize: 12,
    color: '#9ca3af',
  },
  footer: {
    padding: 15,
    backgroundColor: '#fff',
    borderTopWidth: 1,
    borderTopColor: '#e5e7eb',
  },
  contactButton: {
    paddingVertical: 6,
  },
});
