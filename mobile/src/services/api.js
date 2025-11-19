import axios from 'axios';

// IMPORTANT: Change this to your server URL
const API_BASE_URL = 'http://localhost/api';
// For Android emulator use: http://10.0.2.2/api
// For iOS simulator use: http://localhost/api
// For real device use: http://YOUR_COMPUTER_IP/api

class ApiService {
  constructor() {
    this.client = axios.create({
      baseURL: API_BASE_URL,
      timeout: 10000,
      headers: {
        'Content-Type': 'application/json',
      },
    });

    this.token = null;

    // Request interceptor
    this.client.interceptors.request.use(
      (config) => {
        if (this.token) {
          config.headers.Authorization = `Bearer ${this.token}`;
        }
        return config;
      },
      (error) => Promise.reject(error)
    );

    // Response interceptor
    this.client.interceptors.response.use(
      (response) => response.data,
      (error) => {
        if (error.response) {
          return Promise.reject(error.response.data);
        }
        return Promise.reject(error);
      }
    );
  }

  setToken(token) {
    this.token = token;
  }

  // Generic HTTP methods
  async get(url, params = {}) {
    try {
      return await this.client.get(url, {params});
    } catch (error) {
      throw this.handleError(error);
    }
  }

  async post(url, data = {}) {
    try {
      return await this.client.post(url, data);
    } catch (error) {
      throw this.handleError(error);
    }
  }

  async put(url, data = {}) {
    try {
      return await this.client.put(url, data);
    } catch (error) {
      throw this.handleError(error);
    }
  }

  async delete(url) {
    try {
      return await this.client.delete(url);
    } catch (error) {
      throw this.handleError(error);
    }
  }

  // File upload
  async uploadFile(url, file, fieldName = 'image') {
    try {
      const formData = new FormData();
      formData.append(fieldName, {
        uri: file.uri,
        type: file.type || 'image/jpeg',
        name: file.fileName || 'photo.jpg',
      });

      return await this.client.post(url, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });
    } catch (error) {
      throw this.handleError(error);
    }
  }

  handleError(error) {
    console.error('API Error:', error);

    if (error.error) {
      return new Error(error.error);
    }

    if (error.message) {
      return new Error(error.message);
    }

    return new Error('An unexpected error occurred');
  }

  // =================================
  // API ENDPOINTS
  // =================================

  // Auth
  login(email, password) {
    return this.post('/auth/login', {email, password});
  }

  register(userData) {
    return this.post('/auth/register', userData);
  }

  getCurrentUser() {
    return this.get('/auth/me');
  }

  refreshToken() {
    return this.post('/auth/refresh');
  }

  // Projects
  getProjects(params = {}) {
    return this.get('/projects', params);
  }

  getProject(id) {
    return this.get(`/projects/${id}`);
  }

  createProject(data) {
    return this.post('/projects', data);
  }

  updateProject(id, data) {
    return this.put(`/projects/${id}`, data);
  }

  deleteProject(id) {
    return this.delete(`/projects/${id}`);
  }

  // Quotes
  getQuotes(params = {}) {
    return this.get('/quotes', params);
  }

  getQuote(id) {
    return this.get(`/quotes/${id}`);
  }

  createQuote(data) {
    return this.post('/quotes', data);
  }

  acceptQuote(id) {
    return this.put(`/quotes/${id}/accept`);
  }

  rejectQuote(id) {
    return this.put(`/quotes/${id}/reject`);
  }

  // Messages
  getConversations() {
    return this.get('/messages');
  }

  getMessages(userId) {
    return this.get(`/messages/${userId}`);
  }

  sendMessage(data) {
    return this.post('/messages', data);
  }

  // Artisans
  searchArtisans(params = {}) {
    return this.get('/artisans', params);
  }

  getArtisan(id) {
    return this.get(`/artisans/${id}`);
  }

  // Reviews
  createReview(data) {
    return this.post('/reviews', data);
  }

  // Notifications
  getNotifications() {
    return this.get('/notifications');
  }

  markNotificationRead(id) {
    return this.put(`/notifications/${id}/read`);
  }

  markAllNotificationsRead() {
    return this.put('/notifications/read-all');
  }

  // Dashboard Stats
  getDashboardStats() {
    return this.get('/stats/dashboard');
  }

  // Upload
  uploadImage(file) {
    return this.uploadFile('/upload', file, 'image');
  }

  // Categories & Countries
  getCountries() {
    return this.get('/countries');
  }

  getCategories() {
    return this.get('/categories');
  }

  getCategoryFields(categoryId) {
    return this.get(`/categories/${categoryId}/fields`);
  }

  // Trades
  getTrades(params = {}) {
    return this.get('/trades', params);
  }

  getTrade(id) {
    return this.get(`/trades/${id}`);
  }

  getTradeFields(tradeId) {
    return this.get(`/api/trades/${tradeId}/fields`);
  }

  // Favorites
  getFavorites() {
    return this.get('/favorites');
  }

  toggleFavorite(artisanId) {
    return this.post(`/favorites/toggle/${artisanId}`);
  }

  // Analytics
  getMarketPrices(params = {}) {
    return this.get('/analytics/market-prices', params);
  }

  getProjectComparison(projectId) {
    return this.get(`/analytics/compare-project/${projectId}`);
  }
}

export const apiService = new ApiService();
export default apiService;
