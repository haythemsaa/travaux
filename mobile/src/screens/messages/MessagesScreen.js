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
  Avatar,
  Badge,
  Surface,
  ActivityIndicator,
} from 'react-native-paper';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

export default function MessagesScreen({navigation}) {
  const {user} = useAuth();
  const [conversations, setConversations] = useState([]);
  const [filteredConversations, setFilteredConversations] = useState([]);
  const [loading, setLoading] = useState(true);
  const [refreshing, setRefreshing] = useState(false);
  const [searchQuery, setSearchQuery] = useState('');

  useEffect(() => {
    loadConversations();
  }, []);

  useEffect(() => {
    filterConversations();
  }, [searchQuery, conversations]);

  const loadConversations = async () => {
    try {
      const response = await apiService.getConversations();
      if (response.success) {
        setConversations(response.data.conversations);
      }
    } catch (error) {
      console.error('Error loading conversations:', error);
    } finally {
      setLoading(false);
      setRefreshing(false);
    }
  };

  const onRefresh = useCallback(() => {
    setRefreshing(true);
    loadConversations();
  }, []);

  const filterConversations = () => {
    if (!searchQuery.trim()) {
      setFilteredConversations(conversations);
      return;
    }

    const filtered = conversations.filter(conv =>
      conv.user_name.toLowerCase().includes(searchQuery.toLowerCase()),
    );
    setFilteredConversations(filtered);
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

  const getInitials = name => {
    const names = name.split(' ');
    if (names.length >= 2) {
      return (names[0][0] + names[1][0]).toUpperCase();
    }
    return name.substring(0, 2).toUpperCase();
  };

  const renderConversation = ({item}) => (
    <TouchableOpacity
      onPress={() =>
        navigation.navigate('Chat', {
          userId: item.user_id,
          userName: item.user_name,
        })
      }>
      <Surface style={styles.conversationCard}>
        <View style={styles.avatarContainer}>
          <Avatar.Text
            size={50}
            label={getInitials(item.user_name)}
            style={styles.avatar}
          />
          {item.unread_count > 0 && (
            <Badge style={styles.badge}>{item.unread_count}</Badge>
          )}
        </View>

        <View style={styles.conversationContent}>
          <View style={styles.conversationHeader}>
            <Text style={styles.userName} numberOfLines={1}>
              {item.user_name}
            </Text>
            <Text style={styles.timestamp}>
              {formatTime(item.last_message_at)}
            </Text>
          </View>

          <Text
            style={[
              styles.lastMessage,
              item.unread_count > 0 && styles.unreadMessage,
            ]}
            numberOfLines={2}>
            {item.last_message}
          </Text>
        </View>
      </Surface>
    </TouchableOpacity>
  );

  const renderEmpty = () => (
    <View style={styles.emptyContainer}>
      <Text style={styles.emptyText}>Aucune conversation</Text>
      <Text style={styles.emptySubtext}>
        Vos conversations apparaîtront ici
      </Text>
    </View>
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
      <Searchbar
        placeholder="Rechercher une conversation..."
        onChangeText={setSearchQuery}
        value={searchQuery}
        style={styles.searchBar}
      />

      <FlatList
        data={filteredConversations}
        renderItem={renderConversation}
        keyExtractor={item => item.user_id.toString()}
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
  listContainer: {
    padding: 15,
    paddingTop: 0,
  },
  conversationCard: {
    flexDirection: 'row',
    padding: 15,
    marginBottom: 10,
    borderRadius: 10,
    elevation: 2,
    backgroundColor: '#fff',
  },
  avatarContainer: {
    position: 'relative',
    marginRight: 15,
  },
  avatar: {
    backgroundColor: '#3b82f6',
  },
  badge: {
    position: 'absolute',
    top: -5,
    right: -5,
    backgroundColor: '#ef4444',
  },
  conversationContent: {
    flex: 1,
  },
  conversationHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 5,
  },
  userName: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
    flex: 1,
  },
  timestamp: {
    fontSize: 12,
    color: '#6b7280',
    marginLeft: 10,
  },
  lastMessage: {
    fontSize: 14,
    color: '#6b7280',
    lineHeight: 20,
  },
  unreadMessage: {
    fontWeight: '600',
    color: '#111827',
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
    marginBottom: 8,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#9ca3af',
  },
});
