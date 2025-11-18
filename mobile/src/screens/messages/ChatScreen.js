import React, {useState, useEffect, useRef} from 'react';
import {
  View,
  StyleSheet,
  FlatList,
  KeyboardAvoidingView,
  Platform,
  TouchableOpacity,
} from 'react-native';
import {
  Text,
  TextInput,
  Avatar,
  Surface,
  ActivityIndicator,
  IconButton,
} from 'react-native-paper';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

export default function ChatScreen({route, navigation}) {
  const {userId, userName} = route.params;
  const {user} = useAuth();
  const [messages, setMessages] = useState([]);
  const [loading, setLoading] = useState(true);
  const [message, setMessage] = useState('');
  const [sending, setSending] = useState(false);
  const flatListRef = useRef(null);

  useEffect(() => {
    navigation.setOptions({
      title: userName,
    });
    loadMessages();

    // Poll for new messages every 5 seconds
    const interval = setInterval(loadMessages, 5000);
    return () => clearInterval(interval);
  }, [userId]);

  const loadMessages = async () => {
    try {
      const response = await apiService.getMessages(userId);
      if (response.success) {
        setMessages(response.data.messages.reverse());
        // Mark as read
        await apiService.markMessagesAsRead(userId);
      }
    } catch (error) {
      console.error('Error loading messages:', error);
    } finally {
      setLoading(false);
    }
  };

  const sendMessage = async () => {
    if (!message.trim() || sending) return;

    setSending(true);
    try {
      const response = await apiService.sendMessage({
        receiver_id: userId,
        message: message.trim(),
      });

      if (response.success) {
        setMessage('');
        await loadMessages();
        flatListRef.current?.scrollToEnd({animated: true});
      }
    } catch (error) {
      console.error('Error sending message:', error);
    } finally {
      setSending(false);
    }
  };

  const formatTime = timestamp => {
    const date = new Date(timestamp);
    return date.toLocaleTimeString('fr-FR', {
      hour: '2-digit',
      minute: '2-digit',
    });
  };

  const formatDate = timestamp => {
    const date = new Date(timestamp);
    const today = new Date();
    const yesterday = new Date(today);
    yesterday.setDate(yesterday.getDate() - 1);

    if (date.toDateString() === today.toDateString()) {
      return "Aujourd'hui";
    } else if (date.toDateString() === yesterday.toDateString()) {
      return 'Hier';
    }
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: 'long',
    });
  };

  const shouldShowDate = (currentMsg, previousMsg) => {
    if (!previousMsg) return true;
    const currentDate = new Date(currentMsg.created_at).toDateString();
    const previousDate = new Date(previousMsg.created_at).toDateString();
    return currentDate !== previousDate;
  };

  const renderMessage = ({item, index}) => {
    const isMe = item.sender_id === user.id;
    const showDate = shouldShowDate(item, messages[index - 1]);

    return (
      <View>
        {showDate && (
          <View style={styles.dateContainer}>
            <Text style={styles.dateText}>
              {formatDate(item.created_at)}
            </Text>
          </View>
        )}

        <View
          style={[
            styles.messageContainer,
            isMe ? styles.myMessageContainer : styles.theirMessageContainer,
          ]}>
          {!isMe && (
            <Avatar.Text
              size={32}
              label={userName.substring(0, 2).toUpperCase()}
              style={styles.avatar}
            />
          )}

          <Surface
            style={[
              styles.messageBubble,
              isMe ? styles.myMessage : styles.theirMessage,
            ]}>
            <Text
              style={[
                styles.messageText,
                isMe ? styles.myMessageText : styles.theirMessageText,
              ]}>
              {item.message}
            </Text>
            <Text
              style={[
                styles.messageTime,
                isMe ? styles.myMessageTime : styles.theirMessageTime,
              ]}>
              {formatTime(item.created_at)}
            </Text>
          </Surface>

          {isMe && <View style={styles.avatarPlaceholder} />}
        </View>
      </View>
    );
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : undefined}
      keyboardVerticalOffset={Platform.OS === 'ios' ? 90 : 0}>
      <FlatList
        ref={flatListRef}
        data={messages}
        renderItem={renderMessage}
        keyExtractor={item => item.id.toString()}
        contentContainerStyle={styles.messagesList}
        onContentSizeChange={() =>
          flatListRef.current?.scrollToEnd({animated: true})
        }
        ListEmptyComponent={() => (
          <View style={styles.emptyContainer}>
            <Text style={styles.emptyText}>Aucun message</Text>
            <Text style={styles.emptySubtext}>
              Commencez la conversation avec {userName}
            </Text>
          </View>
        )}
      />

      <View style={styles.inputContainer}>
        <TextInput
          mode="outlined"
          placeholder="Votre message..."
          value={message}
          onChangeText={setMessage}
          style={styles.input}
          multiline
          maxLength={1000}
          right={
            <TextInput.Icon
              icon="send"
              onPress={sendMessage}
              disabled={!message.trim() || sending}
              color={message.trim() && !sending ? '#3b82f6' : '#9ca3af'}
            />
          }
        />
      </View>
    </KeyboardAvoidingView>
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
  messagesList: {
    padding: 15,
    flexGrow: 1,
  },
  dateContainer: {
    alignItems: 'center',
    marginVertical: 15,
  },
  dateText: {
    fontSize: 12,
    color: '#6b7280',
    backgroundColor: '#e5e7eb',
    paddingHorizontal: 12,
    paddingVertical: 4,
    borderRadius: 12,
  },
  messageContainer: {
    flexDirection: 'row',
    marginBottom: 10,
    alignItems: 'flex-end',
  },
  myMessageContainer: {
    justifyContent: 'flex-end',
  },
  theirMessageContainer: {
    justifyContent: 'flex-start',
  },
  avatar: {
    backgroundColor: '#6b7280',
    marginRight: 8,
  },
  avatarPlaceholder: {
    width: 32,
    marginLeft: 8,
  },
  messageBubble: {
    maxWidth: '75%',
    padding: 12,
    borderRadius: 16,
    elevation: 1,
  },
  myMessage: {
    backgroundColor: '#3b82f6',
    borderBottomRightRadius: 4,
  },
  theirMessage: {
    backgroundColor: '#fff',
    borderBottomLeftRadius: 4,
  },
  messageText: {
    fontSize: 15,
    lineHeight: 20,
    marginBottom: 4,
  },
  myMessageText: {
    color: '#fff',
  },
  theirMessageText: {
    color: '#111827',
  },
  messageTime: {
    fontSize: 11,
  },
  myMessageTime: {
    color: 'rgba(255, 255, 255, 0.7)',
    textAlign: 'right',
  },
  theirMessageTime: {
    color: '#9ca3af',
  },
  inputContainer: {
    padding: 15,
    backgroundColor: '#fff',
    borderTopWidth: 1,
    borderTopColor: '#e5e7eb',
  },
  input: {
    backgroundColor: '#fff',
    maxHeight: 100,
  },
  emptyContainer: {
    flex: 1,
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
    textAlign: 'center',
  },
});
