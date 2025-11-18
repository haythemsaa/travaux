import React, {useEffect, useState} from 'react';
import {
  View,
  StyleSheet,
  ScrollView,
  RefreshControl,
  TouchableOpacity,
} from 'react-native';
import {
  Title,
  Text,
  Card,
  Button,
  Avatar,
  IconButton,
  ActivityIndicator,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import {useAuth} from '../../context/AuthContext';
import {apiService} from '../../services/api';

export default function HomeScreen({navigation}) {
  const {user} = useAuth();
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadDashboardData();
  }, []);

  const loadDashboardData = async () => {
    try {
      const response = await apiService.getDashboardStats();
      if (response.success) {
        setStats(response.data.stats);
      }
    } catch (error) {
      console.error('Error loading dashboard:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = () => {
    setRefreshing(true);
    loadDashboardData();
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  return (
    <ScrollView
      style={styles.container}
      refreshControl={
        <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
      }>
      {/* Header */}
      <View style={styles.header}>
        <View>
          <Text style={styles.greeting}>Bonjour,</Text>
          <Title style={styles.userName}>
            {user?.first_name} {user?.last_name}
          </Title>
        </View>
        <TouchableOpacity onPress={() => navigation.navigate('Notifications')}>
          <IconButton icon="bell" size={24} />
        </TouchableOpacity>
      </View>

      {/* Quick Actions */}
      {user?.role === 'client' ? (
        <Card style={styles.card}>
          <Card.Content>
            <Title>Actions rapides</Title>
            <View style={styles.quickActions}>
              <TouchableOpacity
                style={styles.actionButton}
                onPress={() => navigation.navigate('CreateProject')}>
                <Icon name="plus-circle" size={40} color="#3b82f6" />
                <Text style={styles.actionText}>Nouveau projet</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={styles.actionButton}
                onPress={() => navigation.navigate('Artisans')}>
                <Icon name="account-search" size={40} color="#3b82f6" />
                <Text style={styles.actionText}>Trouver artisan</Text>
              </TouchableOpacity>
              <TouchableOpacity
                style={styles.actionButton}
                onPress={() => navigation.navigate('Messages')}>
                <Icon name="message-text" size={40} color="#3b82f6" />
                <Text style={styles.actionText}>Messages</Text>
              </TouchableOpacity>
            </View>
          </Card.Content>
        </Card>
      ) : null}

      {/* Statistics Cards */}
      <View style={styles.statsGrid}>
        {user?.role === 'client' ? (
          <>
            <Card style={styles.statCard}>
              <Card.Content>
                <Icon name="folder" size={32} color="#3b82f6" />
                <Title style={styles.statValue}>
                  {stats?.total_projects || 0}
                </Title>
                <Text style={styles.statLabel}>Projets</Text>
              </Card.Content>
            </Card>
            <Card style={styles.statCard}>
              <Card.Content>
                <Icon name="file-document" size={32} color="#10b981" />
                <Title style={styles.statValue}>
                  {stats?.total_quotes || 0}
                </Title>
                <Text style={styles.statLabel}>Devis reçus</Text>
              </Card.Content>
            </Card>
          </>
        ) : (
          <>
            <Card style={styles.statCard}>
              <Card.Content>
                <Icon name="eye" size={32} color="#3b82f6" />
                <Title style={styles.statValue}>
                  {stats?.projects_viewed || 0}
                </Title>
                <Text style={styles.statLabel}>Projets vus</Text>
              </Card.Content>
            </Card>
            <Card style={styles.statCard}>
              <Card.Content>
                <Icon name="file-send" size={32} color="#10b981" />
                <Title style={styles.statValue}>
                  {stats?.quotes_sent || 0}
                </Title>
                <Text style={styles.statLabel}>Devis envoyés</Text>
              </Card.Content>
            </Card>
            <Card style={styles.statCard}>
              <Card.Content>
                <Icon name="check-circle" size={32} color="#f59e0b" />
                <Title style={styles.statValue}>
                  {stats?.quotes_accepted || 0}
                </Title>
                <Text style={styles.statLabel}>Acceptés</Text>
              </Card.Content>
            </Card>
            <Card style={styles.statCard}>
              <Card.Content>
                <Icon name="trending-up" size={32} color="#8b5cf6" />
                <Title style={styles.statValue}>
                  {stats?.conversion_rate?.toFixed(1) || 0}%
                </Title>
                <Text style={styles.statLabel}>Conversion</Text>
              </Card.Content>
            </Card>
          </>
        )}
      </View>

      {/* Recent Projects */}
      {user?.role === 'client' && stats?.recent_projects ? (
        <Card style={styles.card}>
          <Card.Content>
            <View style={styles.sectionHeader}>
              <Title>Projets récents</Title>
              <Button
                mode="text"
                onPress={() => navigation.navigate('Projects')}>
                Voir tout
              </Button>
            </View>
            {stats.recent_projects.map((project) => (
              <TouchableOpacity
                key={project.id}
                onPress={() =>
                  navigation.navigate('ProjectDetail', {id: project.id})
                }>
                <View style={styles.projectItem}>
                  <View style={styles.projectIcon}>
                    <Icon name="folder" size={24} color="#3b82f6" />
                  </View>
                  <View style={styles.projectInfo}>
                    <Text style={styles.projectTitle}>{project.title}</Text>
                    <Text style={styles.projectDate}>
                      {new Date(project.created_at).toLocaleDateString()}
                    </Text>
                  </View>
                  <View style={styles.projectStatus}>
                    <Text
                      style={[
                        styles.statusBadge,
                        {backgroundColor: getStatusColor(project.status)},
                      ]}>
                      {project.status}
                    </Text>
                  </View>
                </View>
              </TouchableOpacity>
            ))}
          </Card.Content>
        </Card>
      ) : null}

      {/* Recent Quotes */}
      {user?.role === 'artisan' && stats?.recent_quotes ? (
        <Card style={styles.card}>
          <Card.Content>
            <View style={styles.sectionHeader}>
              <Title>Devis récents</Title>
              <Button mode="text" onPress={() => navigation.navigate('Quotes')}>
                Voir tout
              </Button>
            </View>
            {stats.recent_quotes.map((quote) => (
              <TouchableOpacity
                key={quote.id}
                onPress={() =>
                  navigation.navigate('QuoteDetail', {id: quote.id})
                }>
                <View style={styles.projectItem}>
                  <View style={styles.projectIcon}>
                    <Icon name="file-document" size={24} color="#10b981" />
                  </View>
                  <View style={styles.projectInfo}>
                    <Text style={styles.projectTitle}>
                      {quote.amount} {quote.currency_code || 'EUR'}
                    </Text>
                    <Text style={styles.projectDate}>
                      {new Date(quote.created_at).toLocaleDateString()}
                    </Text>
                  </View>
                  <View style={styles.projectStatus}>
                    <Text
                      style={[
                        styles.statusBadge,
                        {backgroundColor: getStatusColor(quote.status)},
                      ]}>
                      {quote.status}
                    </Text>
                  </View>
                </View>
              </TouchableOpacity>
            ))}
          </Card.Content>
        </Card>
      ) : null}
    </ScrollView>
  );
}

function getStatusColor(status) {
  switch (status) {
    case 'open':
    case 'pending':
      return '#3b82f6';
    case 'accepted':
    case 'completed':
      return '#10b981';
    case 'rejected':
    case 'cancelled':
      return '#ef4444';
    default:
      return '#6b7280';
  }
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
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 20,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#e5e7eb',
  },
  greeting: {
    fontSize: 14,
    color: '#6b7280',
  },
  userName: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#111827',
  },
  card: {
    margin: 15,
    marginBottom: 10,
  },
  quickActions: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginTop: 15,
  },
  actionButton: {
    alignItems: 'center',
  },
  actionText: {
    marginTop: 8,
    fontSize: 12,
    color: '#374151',
  },
  statsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    padding: 15,
    paddingTop: 5,
  },
  statCard: {
    width: '48%',
    margin: '1%',
    marginBottom: 10,
  },
  statValue: {
    fontSize: 28,
    fontWeight: 'bold',
    marginVertical: 8,
  },
  statLabel: {
    fontSize: 12,
    color: '#6b7280',
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 10,
  },
  projectItem: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingVertical: 12,
    borderBottomWidth: 1,
    borderBottomColor: '#f3f4f6',
  },
  projectIcon: {
    marginRight: 12,
  },
  projectInfo: {
    flex: 1,
  },
  projectTitle: {
    fontSize: 16,
    fontWeight: '500',
    color: '#111827',
  },
  projectDate: {
    fontSize: 12,
    color: '#6b7280',
    marginTop: 4,
  },
  projectStatus: {},
  statusBadge: {
    paddingHorizontal: 8,
    paddingVertical: 4,
    borderRadius: 12,
    fontSize: 11,
    color: '#fff',
    fontWeight: 'bold',
    overflow: 'hidden',
  },
});
