import React, {useEffect, useState} from 'react';
import {
  View,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
  Image,
  Alert,
} from 'react-native';
import {
  Title,
  Text,
  Card,
  Button,
  Chip,
  Divider,
  ActivityIndicator,
  FAB,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import {useAuth} from '../../context/AuthContext';
import {apiService} from '../../services/api';

export default function ProjectDetailScreen({route, navigation}) {
  const {id} = route.params;
  const {user} = useAuth();
  const [project, setProject] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadProject();
  }, [id]);

  const loadProject = async () => {
    try {
      const response = await apiService.getProject(id);
      if (response.success) {
        setProject(response.data.project);
      }
    } catch (error) {
      console.error('Error loading project:', error);
      Alert.alert('Erreur', 'Impossible de charger le projet');
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadProject();
  };

  const handleDeleteProject = () => {
    Alert.alert(
      'Supprimer le projet',
      'Êtes-vous sûr de vouloir supprimer ce projet ?',
      [
        {text: 'Annuler', style: 'cancel'},
        {
          text: 'Supprimer',
          style: 'destructive',
          onPress: async () => {
            try {
              const response = await apiService.deleteProject(id);
              if (response.success) {
                Alert.alert('Succès', 'Projet supprimé');
                navigation.goBack();
              }
            } catch (error) {
              Alert.alert('Erreur', 'Impossible de supprimer le projet');
            }
          },
        },
      ]
    );
  };

  const getStatusColor = status => {
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
  };

  const getStatusLabel = status => {
    const labels = {
      open: 'Ouvert',
      in_progress: 'En cours',
      completed: 'Terminé',
      cancelled: 'Annulé',
    };
    return labels[status] || status;
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  if (!project) {
    return (
      <View style={styles.emptyContainer}>
        <Icon name="folder-alert" size={64} color="#d1d5db" />
        <Text style={styles.emptyText}>Projet introuvable</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <ScrollView
        refreshControl={
          <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
        }>
        {/* Header Card */}
        <Card style={styles.card}>
          <Card.Content>
            <View style={styles.header}>
              <Title style={styles.title}>{project.title}</Title>
              <Chip
                style={[
                  styles.statusChip,
                  {backgroundColor: getStatusColor(project.status)},
                ]}
                textStyle={styles.statusText}>
                {getStatusLabel(project.status)}
              </Chip>
            </View>

            <View style={styles.infoRow}>
              <Icon name="map-marker" size={16} color="#6b7280" />
              <Text style={styles.infoText}>
                {project.city}, {project.postal_code}
              </Text>
            </View>

            <View style={styles.infoRow}>
              <Icon name="calendar" size={16} color="#6b7280" />
              <Text style={styles.infoText}>
                Créé le {new Date(project.created_at).toLocaleDateString('fr-FR')}
              </Text>
            </View>

            {project.preferred_date && (
              <View style={styles.infoRow}>
                <Icon name="calendar-check" size={16} color="#6b7280" />
                <Text style={styles.infoText}>
                  Date souhaitée:{' '}
                  {new Date(project.preferred_date).toLocaleDateString('fr-FR')}
                </Text>
              </View>
            )}
          </Card.Content>
        </Card>

        {/* Description */}
        <Card style={styles.card}>
          <Card.Content>
            <Title style={styles.sectionTitle}>Description</Title>
            <Text style={styles.description}>{project.description}</Text>
          </Card.Content>
        </Card>

        {/* Budget */}
        {(project.budget_min || project.budget_max) && (
          <Card style={styles.card}>
            <Card.Content>
              <Title style={styles.sectionTitle}>Budget</Title>
              <View style={styles.budgetContainer}>
                <Icon name="currency-eur" size={24} color="#3b82f6" />
                <Text style={styles.budgetText}>
                  {project.budget_min && project.budget_max
                    ? `${project.budget_min} - ${project.budget_max} €`
                    : project.budget_max
                    ? `Max ${project.budget_max} €`
                    : `Min ${project.budget_min} €`}
                </Text>
              </View>
            </Card.Content>
          </Card>
        )}

        {/* Photos */}
        {project.photos && project.photos.length > 0 && (
          <Card style={styles.card}>
            <Card.Content>
              <Title style={styles.sectionTitle}>Photos</Title>
              <ScrollView horizontal showsHorizontalScrollIndicator={false}>
                {project.photos.map((photo, index) => (
                  <Image
                    key={index}
                    source={{uri: photo.url}}
                    style={styles.photo}
                  />
                ))}
              </ScrollView>
            </Card.Content>
          </Card>
        )}

        {/* Custom Fields */}
        {project.custom_fields && project.custom_fields.length > 0 && (
          <Card style={styles.card}>
            <Card.Content>
              <Title style={styles.sectionTitle}>Détails spécifiques</Title>
              {project.custom_fields.map((field, index) => (
                <View key={index} style={styles.customField}>
                  <Text style={styles.customFieldLabel}>{field.label}:</Text>
                  <Text style={styles.customFieldValue}>
                    {Array.isArray(field.field_value)
                      ? field.field_value.join(', ')
                      : field.field_value}
                  </Text>
                </View>
              ))}
            </Card.Content>
          </Card>
        )}

        {/* Quotes Received (for client) */}
        {user?.role === 'client' && project.quotes && (
          <Card style={styles.card}>
            <Card.Content>
              <View style={styles.sectionHeader}>
                <Title style={styles.sectionTitle}>
                  Devis reçus ({project.quotes.length})
                </Title>
                {project.quotes.length > 0 && (
                  <Button
                    mode="text"
                    onPress={() =>
                      navigation.navigate('QuoteComparison', {projectId: id})
                    }>
                    Comparer
                  </Button>
                )}
              </View>

              {project.quotes.length === 0 ? (
                <Text style={styles.noQuotesText}>Aucun devis reçu</Text>
              ) : (
                project.quotes.map(quote => (
                  <TouchableOpacity
                    key={quote.id}
                    onPress={() =>
                      navigation.navigate('QuoteDetail', {id: quote.id})
                    }>
                    <View style={styles.quoteItem}>
                      <View style={styles.quoteInfo}>
                        <Text style={styles.quoteName}>
                          {quote.artisan_name}
                        </Text>
                        <Text style={styles.quoteAmount}>
                          {quote.amount} {quote.currency_code || 'EUR'}
                        </Text>
                      </View>
                      <Chip
                        style={{
                          backgroundColor: getStatusColor(quote.status),
                        }}
                        textStyle={styles.statusText}>
                        {getStatusLabel(quote.status)}
                      </Chip>
                    </View>
                  </TouchableOpacity>
                ))
              )}
            </Card.Content>
          </Card>
        )}

        {/* Actions for Client */}
        {user?.role === 'client' && project.client_id === user.id && (
          <Card style={styles.card}>
            <Card.Content>
              <Title style={styles.sectionTitle}>Actions</Title>
              <Button
                mode="outlined"
                icon="pencil"
                onPress={() =>
                  navigation.navigate('EditProject', {project})
                }
                style={styles.actionButton}>
                Modifier le projet
              </Button>
              <Button
                mode="outlined"
                icon="delete"
                onPress={handleDeleteProject}
                style={styles.actionButton}
                textColor="#ef4444">
                Supprimer le projet
              </Button>
            </Card.Content>
          </Card>
        )}
      </ScrollView>

      {/* FAB for Artisan to send quote */}
      {user?.role === 'artisan' && project.status === 'open' && (
        <FAB
          style={styles.fab}
          icon="file-send"
          label="Envoyer un devis"
          onPress={() =>
            navigation.navigate('CreateQuote', {projectId: id, project})
          }
        />
      )}
    </View>
  );
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
  emptyContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    padding: 20,
  },
  emptyText: {
    fontSize: 18,
    color: '#6b7280',
    marginTop: 16,
  },
  card: {
    margin: 15,
    marginBottom: 0,
    marginTop: 15,
  },
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'flex-start',
    marginBottom: 16,
  },
  title: {
    flex: 1,
    fontSize: 22,
    fontWeight: 'bold',
    marginRight: 10,
  },
  statusChip: {
    height: 32,
  },
  statusText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: 'bold',
  },
  infoRow: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 8,
  },
  infoText: {
    marginLeft: 8,
    fontSize: 14,
    color: '#6b7280',
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 12,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  description: {
    fontSize: 15,
    lineHeight: 22,
    color: '#374151',
  },
  budgetContainer: {
    flexDirection: 'row',
    alignItems: 'center',
  },
  budgetText: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#3b82f6',
    marginLeft: 12,
  },
  photo: {
    width: 150,
    height: 150,
    borderRadius: 8,
    marginRight: 10,
  },
  customField: {
    marginBottom: 12,
  },
  customFieldLabel: {
    fontSize: 14,
    fontWeight: '500',
    color: '#6b7280',
    marginBottom: 4,
  },
  customFieldValue: {
    fontSize: 15,
    color: '#111827',
  },
  noQuotesText: {
    fontSize: 14,
    color: '#9ca3af',
    textAlign: 'center',
    paddingVertical: 20,
  },
  quoteItem: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f3f4f6',
  },
  quoteInfo: {
    flex: 1,
  },
  quoteName: {
    fontSize: 16,
    fontWeight: '500',
    color: '#111827',
    marginBottom: 4,
  },
  quoteAmount: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#3b82f6',
  },
  actionButton: {
    marginTop: 10,
  },
  fab: {
    position: 'absolute',
    margin: 16,
    right: 0,
    bottom: 0,
    backgroundColor: '#3b82f6',
  },
});
