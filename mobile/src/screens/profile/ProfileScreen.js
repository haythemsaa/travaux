import React, {useState, useEffect} from 'react';
import {View, StyleSheet, ScrollView, Alert} from 'react-native';
import {
  Text,
  Avatar,
  Surface,
  List,
  Divider,
  ActivityIndicator,
  Button,
  Dialog,
  Portal,
  Paragraph,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

export default function ProfileScreen({navigation}) {
  const {user, logout} = useAuth();
  const [profile, setProfile] = useState(null);
  const [stats, setStats] = useState(null);
  const [loading, setLoading] = useState(true);
  const [logoutDialogVisible, setLogoutDialogVisible] = useState(false);

  useEffect(() => {
    loadProfile();
    loadStats();
  }, []);

  const loadProfile = async () => {
    try {
      const response = await apiService.getCurrentUser();
      if (response.success) {
        setProfile(response.data.user);
      }
    } catch (error) {
      console.error('Error loading profile:', error);
    } finally {
      setLoading(false);
    }
  };

  const loadStats = async () => {
    try {
      const response = await apiService.getDashboardStats();
      if (response.success) {
        setStats(response.data.stats);
      }
    } catch (error) {
      console.error('Error loading stats:', error);
    }
  };

  const handleLogout = async () => {
    setLogoutDialogVisible(false);
    await logout();
  };

  const getInitials = () => {
    if (!profile) return 'U';
    const firstName = profile.first_name || '';
    const lastName = profile.last_name || '';
    if (firstName && lastName) {
      return (firstName[0] + lastName[0]).toUpperCase();
    }
    return profile.email?.substring(0, 2).toUpperCase() || 'U';
  };

  const getRoleBadge = () => {
    return user?.role === 'artisan' ? 'Professionnel' : 'Particulier';
  };

  const getRoleColor = () => {
    return user?.role === 'artisan' ? '#3b82f6' : '#10b981';
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  return (
    <ScrollView style={styles.container}>
      {/* Profile Header */}
      <Surface style={styles.header}>
        <Avatar.Text size={80} label={getInitials()} style={styles.avatar} />
        <Text style={styles.name}>
          {profile?.first_name} {profile?.last_name}
        </Text>
        <Text style={styles.email}>{profile?.email}</Text>
        <View
          style={[styles.roleBadge, {backgroundColor: getRoleColor() + '20'}]}>
          <Text style={[styles.roleText, {color: getRoleColor()}]}>
            {getRoleBadge()}
          </Text>
        </View>
      </Surface>

      {/* Stats */}
      {stats && (
        <Surface style={styles.statsCard}>
          <Text style={styles.statsTitle}>Statistiques</Text>
          <View style={styles.statsGrid}>
            {user?.role === 'client' ? (
              <>
                <View style={styles.statItem}>
                  <Text style={styles.statValue}>{stats.total_projects || 0}</Text>
                  <Text style={styles.statLabel}>Projets</Text>
                </View>
                <View style={styles.statItem}>
                  <Text style={styles.statValue}>{stats.active_projects || 0}</Text>
                  <Text style={styles.statLabel}>En cours</Text>
                </View>
                <View style={styles.statItem}>
                  <Text style={styles.statValue}>{stats.total_quotes || 0}</Text>
                  <Text style={styles.statLabel}>Devis reçus</Text>
                </View>
              </>
            ) : (
              <>
                <View style={styles.statItem}>
                  <Text style={styles.statValue}>{stats.quotes_sent || 0}</Text>
                  <Text style={styles.statLabel}>Devis envoyés</Text>
                </View>
                <View style={styles.statItem}>
                  <Text style={styles.statValue}>{stats.quotes_accepted || 0}</Text>
                  <Text style={styles.statLabel}>Acceptés</Text>
                </View>
                <View style={styles.statItem}>
                  <Text style={styles.statValue}>
                    {stats.conversion_rate?.toFixed(0) || 0}%
                  </Text>
                  <Text style={styles.statLabel}>Taux conversion</Text>
                </View>
              </>
            )}
          </View>
        </Surface>
      )}

      {/* Menu Items */}
      <Surface style={styles.menuCard}>
        <List.Item
          title="Modifier le profil"
          description="Informations personnelles"
          left={props => <List.Icon {...props} icon="account-edit" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={() => navigation.navigate('EditProfile')}
        />
        <Divider />

        {user?.role === 'artisan' && (
          <>
            <List.Item
              title="Profil professionnel"
              description="Spécialités, tarifs, disponibilités"
              left={props => <List.Icon {...props} icon="briefcase" />}
              right={props => <List.Icon {...props} icon="chevron-right" />}
              onPress={() => navigation.navigate('ArtisanProfile')}
            />
            <Divider />
          </>
        )}

        <List.Item
          title="Paramètres"
          description="Notifications, langue, préférences"
          left={props => <List.Icon {...props} icon="cog" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={() => navigation.navigate('Settings')}
        />
        <Divider />

        <List.Item
          title="Aide & Support"
          description="FAQ, contact, documentation"
          left={props => <List.Icon {...props} icon="help-circle" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={() => navigation.navigate('Help')}
        />
        <Divider />

        <List.Item
          title="Conditions d'utilisation"
          left={props => <List.Icon {...props} icon="file-document" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={() => navigation.navigate('Terms')}
        />
        <Divider />

        <List.Item
          title="Confidentialité"
          left={props => <List.Icon {...props} icon="shield-lock" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={() => navigation.navigate('Privacy')}
        />
      </Surface>

      {/* Logout Button */}
      <Button
        mode="outlined"
        onPress={() => setLogoutDialogVisible(true)}
        style={styles.logoutButton}
        textColor="#ef4444"
        icon="logout">
        Déconnexion
      </Button>

      {/* App Version */}
      <Text style={styles.version}>Version 1.0.0</Text>

      {/* Logout Confirmation Dialog */}
      <Portal>
        <Dialog
          visible={logoutDialogVisible}
          onDismiss={() => setLogoutDialogVisible(false)}>
          <Dialog.Title>Déconnexion</Dialog.Title>
          <Dialog.Content>
            <Paragraph>Êtes-vous sûr de vouloir vous déconnecter ?</Paragraph>
          </Dialog.Content>
          <Dialog.Actions>
            <Button onPress={() => setLogoutDialogVisible(false)}>
              Annuler
            </Button>
            <Button onPress={handleLogout} textColor="#ef4444">
              Déconnexion
            </Button>
          </Dialog.Actions>
        </Dialog>
      </Portal>
    </ScrollView>
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
  email: {
    fontSize: 14,
    color: '#6b7280',
    marginBottom: 12,
  },
  roleBadge: {
    paddingHorizontal: 16,
    paddingVertical: 6,
    borderRadius: 16,
  },
  roleText: {
    fontSize: 13,
    fontWeight: '600',
  },
  statsCard: {
    padding: 20,
    marginHorizontal: 15,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  statsTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 15,
  },
  statsGrid: {
    flexDirection: 'row',
    justifyContent: 'space-around',
  },
  statItem: {
    alignItems: 'center',
  },
  statValue: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#3b82f6',
    marginBottom: 4,
  },
  statLabel: {
    fontSize: 12,
    color: '#6b7280',
    textAlign: 'center',
  },
  menuCard: {
    marginHorizontal: 15,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
    overflow: 'hidden',
  },
  logoutButton: {
    marginHorizontal: 15,
    marginBottom: 15,
    borderColor: '#ef4444',
  },
  version: {
    textAlign: 'center',
    fontSize: 12,
    color: '#9ca3af',
    marginBottom: 30,
  },
});
