import React, {createContext, useState, useContext, useEffect} from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';
import {apiService} from '../services/api';

const AuthContext = createContext({});

export const AuthProvider = ({children}) => {
  const [user, setUser] = useState(null);
  const [token, setToken] = useState(null);
  const [isLoading, setIsLoading] = useState(true);
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  // Load user from storage on app start
  useEffect(() => {
    loadUserFromStorage();
  }, []);

  const loadUserFromStorage = async () => {
    try {
      const storedToken = await AsyncStorage.getItem('token');
      const storedUser = await AsyncStorage.getItem('user');

      if (storedToken && storedUser) {
        setToken(storedToken);
        setUser(JSON.parse(storedUser));
        setIsAuthenticated(true);
        apiService.setToken(storedToken);
      }
    } catch (error) {
      console.error('Error loading user from storage:', error);
    } finally {
      setIsLoading(false);
    }
  };

  const login = async (email, password) => {
    try {
      const response = await apiService.post('/auth/login', {email, password});

      if (response.success) {
        const {user: userData, token: userToken} = response.data;

        setUser(userData);
        setToken(userToken);
        setIsAuthenticated(true);

        // Store in AsyncStorage
        await AsyncStorage.setItem('token', userToken);
        await AsyncStorage.setItem('user', JSON.stringify(userData));

        // Set token in API service
        apiService.setToken(userToken);

        return {success: true};
      }

      return {success: false, error: response.error};
    } catch (error) {
      console.error('Login error:', error);
      return {success: false, error: error.message};
    }
  };

  const register = async (userData) => {
    try {
      const response = await apiService.post('/auth/register', userData);

      if (response.success) {
        const {user: newUser, token: userToken} = response.data;

        setUser(newUser);
        setToken(userToken);
        setIsAuthenticated(true);

        // Store in AsyncStorage
        await AsyncStorage.setItem('token', userToken);
        await AsyncStorage.setItem('user', JSON.stringify(newUser));

        // Set token in API service
        apiService.setToken(userToken);

        return {success: true};
      }

      return {success: false, error: response.error};
    } catch (error) {
      console.error('Register error:', error);
      return {success: false, error: error.message};
    }
  };

  const logout = async () => {
    try {
      await AsyncStorage.removeItem('token');
      await AsyncStorage.removeItem('user');

      setUser(null);
      setToken(null);
      setIsAuthenticated(false);

      apiService.setToken(null);
    } catch (error) {
      console.error('Logout error:', error);
    }
  };

  const updateUser = async (updatedData) => {
    try {
      const updatedUser = {...user, ...updatedData};
      setUser(updatedUser);
      await AsyncStorage.setItem('user', JSON.stringify(updatedUser));
    } catch (error) {
      console.error('Update user error:', error);
    }
  };

  const value = {
    user,
    token,
    isAuthenticated,
    isLoading,
    login,
    register,
    logout,
    updateUser,
  };

  return <AuthContext.Provider value={value}>{children}</AuthContext.Provider>;
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
