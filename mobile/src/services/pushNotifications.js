import messaging from '@react-native-firebase/messaging';
import {Platform, PermissionsAndroid, Alert} from 'react-native';
import {apiService} from './api';

class PushNotificationService {
  constructor() {
    this.fcmToken = null;
  }

  /**
   * Request permission for notifications
   */
  async requestPermission() {
    try {
      if (Platform.OS === 'android') {
        if (Platform.Version >= 33) {
          const granted = await PermissionsAndroid.request(
            PermissionsAndroid.PERMISSIONS.POST_NOTIFICATIONS
          );
          return granted === PermissionsAndroid.RESULTS.GRANTED;
        }
        return true;
      }

      // iOS
      const authStatus = await messaging().requestPermission();
      const enabled =
        authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
        authStatus === messaging.AuthorizationStatus.PROVISIONAL;

      return enabled;
    } catch (error) {
      console.error('Error requesting notification permission:', error);
      return false;
    }
  }

  /**
   * Get FCM token
   */
  async getToken() {
    try {
      const hasPermission = await this.requestPermission();

      if (!hasPermission) {
        console.log('Notification permission denied');
        return null;
      }

      const token = await messaging().getToken();
      this.fcmToken = token;
      console.log('FCM Token:', token);

      // Send token to backend
      await this.sendTokenToServer(token);

      return token;
    } catch (error) {
      console.error('Error getting FCM token:', error);
      return null;
    }
  }

  /**
   * Send FCM token to backend
   */
  async sendTokenToServer(token) {
    try {
      // TODO: Implement API endpoint to store FCM token
      await apiService.post('/user/fcm-token', {
        fcm_token: token,
        platform: Platform.OS,
      });
    } catch (error) {
      console.error('Error sending token to server:', error);
    }
  }

  /**
   * Listen for token refresh
   */
  onTokenRefresh(callback) {
    return messaging().onTokenRefresh(async token => {
      this.fcmToken = token;
      await this.sendTokenToServer(token);
      if (callback) {
        callback(token);
      }
    });
  }

  /**
   * Handle notification when app is in foreground
   */
  onMessageReceived(callback) {
    return messaging().onMessage(async remoteMessage => {
      console.log('Foreground message:', remoteMessage);

      if (callback) {
        callback(remoteMessage);
      } else {
        // Default: show alert
        Alert.alert(
          remoteMessage.notification?.title || 'Notification',
          remoteMessage.notification?.body || ''
        );
      }
    });
  }

  /**
   * Handle notification when app is in background/quit state
   */
  onBackgroundMessage() {
    messaging().setBackgroundMessageHandler(async remoteMessage => {
      console.log('Background message:', remoteMessage);
      // Process notification data
      return Promise.resolve();
    });
  }

  /**
   * Handle notification open/tap
   */
  onNotificationOpenedApp(callback) {
    // App opened from background state
    messaging().onNotificationOpenedApp(remoteMessage => {
      console.log('Notification opened app from background:', remoteMessage);
      if (callback) {
        callback(remoteMessage);
      }
    });

    // App opened from quit state
    messaging()
      .getInitialNotification()
      .then(remoteMessage => {
        if (remoteMessage) {
          console.log('Notification opened app from quit state:', remoteMessage);
          if (callback) {
            callback(remoteMessage);
          }
        }
      });
  }

  /**
   * Subscribe to topic
   */
  async subscribeToTopic(topic) {
    try {
      await messaging().subscribeToTopic(topic);
      console.log(`Subscribed to topic: ${topic}`);
    } catch (error) {
      console.error(`Error subscribing to topic ${topic}:`, error);
    }
  }

  /**
   * Unsubscribe from topic
   */
  async unsubscribeFromTopic(topic) {
    try {
      await messaging().unsubscribeFromTopic(topic);
      console.log(`Unsubscribed from topic: ${topic}`);
    } catch (error) {
      console.error(`Error unsubscribing from topic ${topic}:`, error);
    }
  }

  /**
   * Get badge count (iOS)
   */
  async getBadgeCount() {
    if (Platform.OS === 'ios') {
      return await messaging().getApplicationBadge();
    }
    return 0;
  }

  /**
   * Set badge count (iOS)
   */
  async setBadgeCount(count) {
    if (Platform.OS === 'ios') {
      await messaging().setApplicationBadge(count);
    }
  }

  /**
   * Initialize push notifications
   */
  async initialize(onMessage, onNotificationTap) {
    console.log('Initializing push notifications...');

    // Check if Firebase is available
    const isSupported = await messaging().isDeviceRegisteredForRemoteMessages();

    if (!isSupported) {
      console.log('This device does not support push notifications');
      return;
    }

    // Request permission and get token
    await this.getToken();

    // Listen for token refresh
    this.onTokenRefresh();

    // Handle foreground messages
    this.onMessageReceived(onMessage);

    // Handle background messages
    this.onBackgroundMessage();

    // Handle notification taps
    this.onNotificationOpenedApp(onNotificationTap);

    console.log('Push notifications initialized successfully');
  }

  /**
   * Check if notifications are enabled
   */
  async areNotificationsEnabled() {
    if (Platform.OS === 'android') {
      return true; // Android doesn't have a runtime check
    }

    const authStatus = await messaging().hasPermission();
    return (
      authStatus === messaging.AuthorizationStatus.AUTHORIZED ||
      authStatus === messaging.AuthorizationStatus.PROVISIONAL
    );
  }
}

export const pushNotificationService = new PushNotificationService();
export default pushNotificationService;
