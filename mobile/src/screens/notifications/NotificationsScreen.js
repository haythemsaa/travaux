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
  IconButton,
  ActivityIndicator,
  Button,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';

export default function NotificationsScreen({navigation}) {
  const [notifications, setNotifications] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);

  useEffect(() => {
    loadNotifications();
  }, []);

  const loadNotifications = async () => {
    try {
      const response = await apiService.getNotifications();
      if (response.success) {
        setNotifications(response.data.notifications);
      }
    } catch (error) {
      console.error('Error loading notifications:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadNotifications();
  }, []);

  const markAsRead = async notificationId => {
    try {
      await apiService.markNotificationRead(notificationId);
      setNotifications(prev =>
        prev.map(n => (n.id === notificationId ? {...n, is_read: true} : n)),
      );
    } catch (error) {
      console.error('Error marking notification as read:', error);
    }
  };

  const markAllAsRead = async () => {
    try {
      await apiService.markAllNotificationsRead();
      setNotifications(prev => prev.map(n => ({...n, is_read: true})));
    } catch (error) {
      console.error('Error marking all as read:', error);
    }
  };

  const handleNotificationPress = (notification) => {
    if (!notification.is_read) {
      markAsRead(notification.id);
    }

    // Navigate based on notification type
    switch (notification.type) {
      case 'new_quote':
      case 'quote_accepted':
      case 'quote_rejected':
        navigation.navigate('QuoteDetail', {quoteId: notification.related_id});
        break;
      case 'new_message':
        navigation.navigate('Messages');
        break;
      case 'new_project':
        navigation.navigate('ProjectDetail', {projectId: notification.related_id});
        break;
      default:
        break;
    }
  };

  const getNotificationIcon = type => {
    const icons = {
      new_quote: 'file-document',
      quote_accepted: 'check-circle',
      quote_rejected: 'close-circle',
      new_message: 'message',
      new_project: 'briefcase',
      payment_success: 'credit-card-check',
      payment_failed: 'credit-card-remove',
    };
    return icons[type] || 'bell';
  };

  const getNotificationColor = type => {
    const colors = {
      new_quote: '#3b82f6',
      quote_accepted: '#10b981',
      quote_rejected: '#ef4444',
      new_message: '#8b5cf6',
      new_project: '#f59e0b',
      payment_success: '#10b981',
      payment_failed: '#ef4444',
    };
    return colors[type] || '#6b7280';
  };

  const formatTime = timestamp => {
    const date = new Date(timestamp);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);

    if (diffMins < 1) return 'À l\'instant';
    if (diffMins < 60) return `Il y a ${diffMins}min`;
    if (diffHours < 24) return `Il y a ${diffHours}h`;
    if (diffDays < 7) return `Il y a ${diffDays}j`;
    return date.toLocaleDateString('fr-FR', {day: '2-digit', month: '2-digit'});
  };

  const renderNotification = ({item}) => (
    <TouchableOpacity onPress={() => handleNotificationPress(item)}>
      <Surface
        style={[
          styles.notificationCard,
          !item.is_read && styles.unreadCard,
        ]}>
        <View
          style={[
            styles.iconContainer,
            {backgroundColor: getNotificationColor(item.type) + '20'},
          ]}>
          <Icon
            name={getNotificationIcon(item.type)}
            size={24}
            color={getNotificationColor(item.type)}
          />
        </View>

        <View style={styles.notificationContent}>
          <Text
            style={[
              styles.notificationTitle,
              !item.is_read && styles.unreadTitle,
            ]}>
            {item.title}
          </Text>
          <Text style={styles.notificationMessage} numberOfLines={2}>
            {item.message}
          </Text>
          <Text style={styles.notificationTime}>
            {formatTime(item.created_at)}
          </Text>
        </View>

        {!item.is_read && <View style={styles.unreadDot} />}
      </Surface>
    </TouchableOpacity>
  );

  const renderEmpty = () => (
    <View style={styles.emptyContainer}>
      <Icon name="bell-off-outline" size={64} color="#d1d5db" />
      <Text style={styles.emptyText}>Aucune notification</Text>
      <Text style={styles.emptySubtext}>
        Vous serez notifié des nouvelles activités ici
      </Text>
    </View>
  );

  const unreadCount = notifications.filter(n => !n.is_read).length;

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  return (
    <View style={styles.container}>
      {unreadCount > 0 && (
        <View style={styles.header}>
          <Text style={styles.headerText}>
            {unreadCount} notification{unreadCount > 1 ? 's' : ''} non lue
            {unreadCount > 1 ? 's' : ''}
          </Text>
          <Button
            mode="text"
            onPress={markAllAsRead}
            compact
            textColor="#3b82f6">
            Tout marquer comme lu
          </Button>
        </View>
      )}

      <FlatList
        data={notifications}
        renderItem={renderNotification}
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
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    padding: 15,
    backgroundColor: '#fff',
    borderBottomWidth: 1,
    borderBottomColor: '#e5e7eb',
  },
  headerText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#374151',
  },
  listContainer: {
    padding: 15,
  },
  notificationCard: {
    flexDirection: 'row',
    padding: 15,
    marginBottom: 10,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
    alignItems: 'flex-start',
  },
  unreadCard: {
    backgroundColor: '#eff6ff',
  },
  iconContainer: {
    width: 48,
    height: 48,
    borderRadius: 24,
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  notificationContent: {
    flex: 1,
  },
  notificationTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#374151',
    marginBottom: 4,
  },
  unreadTitle: {
    fontWeight: 'bold',
    color: '#111827',
  },
  notificationMessage: {
    fontSize: 14,
    color: '#6b7280',
    lineHeight: 20,
    marginBottom: 6,
  },
  notificationTime: {
    fontSize: 12,
    color: '#9ca3af',
  },
  unreadDot: {
    width: 10,
    height: 10,
    borderRadius: 5,
    backgroundColor: '#3b82f6',
    marginLeft: 8,
    marginTop: 4,
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
