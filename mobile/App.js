import React, {useEffect, useState} from 'react';
import {NavigationContainer} from '@react-navigation/native';
import {createStackNavigator} from '@react-navigation/stack';
import {createBottomTabNavigator} from '@react-navigation/bottom-tabs';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import {Provider as PaperProvider} from 'react-native-paper';
import AsyncStorage from '@react-native-async-storage/async-storage';

// Screens
import SplashScreen from './src/screens/SplashScreen';
import LoginScreen from './src/screens/auth/LoginScreen';
import RegisterScreen from './src/screens/auth/RegisterScreen';
import HomeScreen from './src/screens/home/HomeScreen';
import ProjectsScreen from './src/screens/projects/ProjectsScreen';
import ProjectDetailScreen from './src/screens/projects/ProjectDetailScreen';
import CreateProjectScreen from './src/screens/projects/CreateProjectScreen';
import QuotesScreen from './src/screens/quotes/QuotesScreen';
import QuoteDetailScreen from './src/screens/quotes/QuoteDetailScreen';
import CreateQuoteScreen from './src/screens/quotes/CreateQuoteScreen';
import MessagesScreen from './src/screens/messages/MessagesScreen';
import ChatScreen from './src/screens/messages/ChatScreen';
import ProfileScreen from './src/screens/profile/ProfileScreen';
import ArtisansScreen from './src/screens/artisans/ArtisansScreen';
import ArtisanDetailScreen from './src/screens/artisans/ArtisanDetailScreen';
import NotificationsScreen from './src/screens/notifications/NotificationsScreen';
import SettingsScreen from './src/screens/profile/SettingsScreen';
import TradesScreen from './src/screens/trades/TradesScreen';
import TradeDetailScreen from './src/screens/trades/TradeDetailScreen';
import FavoritesScreen from './src/screens/favorites/FavoritesScreen';
import MarketPricesScreen from './src/screens/analytics/MarketPricesScreen';
import HelpScreen from './src/screens/help/HelpScreen';

// Context
import {AuthProvider, useAuth} from './src/context/AuthContext';

const Stack = createStackNavigator();
const Tab = createBottomTabNavigator();

// Bottom Tab Navigator for Client
function ClientTabs() {
  return (
    <Tab.Navigator
      screenOptions={({route}) => ({
        tabBarIcon: ({focused, color, size}) => {
          let iconName;

          if (route.name === 'Home') {
            iconName = 'home';
          } else if (route.name === 'Projects') {
            iconName = 'folder';
          } else if (route.name === 'Artisans') {
            iconName = 'account-hard-hat';
          } else if (route.name === 'Messages') {
            iconName = 'message';
          } else if (route.name === 'Profile') {
            iconName = 'account';
          }

          return <Icon name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: '#3b82f6',
        tabBarInactiveTintColor: 'gray',
      })}>
      <Tab.Screen name="Home" component={HomeScreen} options={{title: 'Accueil'}} />
      <Tab.Screen name="Projects" component={ProjectsScreen} options={{title: 'Mes Projets'}} />
      <Tab.Screen name="Artisans" component={ArtisansScreen} options={{title: 'Artisans'}} />
      <Tab.Screen name="Messages" component={MessagesScreen} options={{title: 'Messages'}} />
      <Tab.Screen name="Profile" component={ProfileScreen} options={{title: 'Profil'}} />
    </Tab.Navigator>
  );
}

// Bottom Tab Navigator for Artisan
function ArtisanTabs() {
  return (
    <Tab.Navigator
      screenOptions={({route}) => ({
        tabBarIcon: ({focused, color, size}) => {
          let iconName;

          if (route.name === 'Home') {
            iconName = 'home';
          } else if (route.name === 'Projects') {
            iconName = 'folder';
          } else if (route.name === 'Quotes') {
            iconName = 'file-document';
          } else if (route.name === 'Messages') {
            iconName = 'message';
          } else if (route.name === 'Profile') {
            iconName = 'account';
          }

          return <Icon name={iconName} size={size} color={color} />;
        },
        tabBarActiveTintColor: '#3b82f6',
        tabBarInactiveTintColor: 'gray',
      })}>
      <Tab.Screen name="Home" component={HomeScreen} options={{title: 'Accueil'}} />
      <Tab.Screen name="Projects" component={ProjectsScreen} options={{title: 'Projets'}} />
      <Tab.Screen name="Quotes" component={QuotesScreen} options={{title: 'Mes Devis'}} />
      <Tab.Screen name="Messages" component={MessagesScreen} options={{title: 'Messages'}} />
      <Tab.Screen name="Profile" component={ProfileScreen} options={{title: 'Profil'}} />
    </Tab.Navigator>
  );
}

// Auth Stack
function AuthStack() {
  return (
    <Stack.Navigator screenOptions={{headerShown: false}}>
      <Stack.Screen name="Login" component={LoginScreen} />
      <Stack.Screen name="Register" component={RegisterScreen} />
    </Stack.Navigator>
  );
}

// Main App Stack
function AppStack() {
  const {user} = useAuth();

  return (
    <Stack.Navigator>
      <Stack.Screen
        name="MainTabs"
        component={user?.role === 'artisan' ? ArtisanTabs : ClientTabs}
        options={{headerShown: false}}
      />
      <Stack.Screen
        name="ProjectDetail"
        component={ProjectDetailScreen}
        options={{title: 'Détails du projet'}}
      />
      <Stack.Screen
        name="CreateProject"
        component={CreateProjectScreen}
        options={{title: 'Nouveau projet'}}
      />
      <Stack.Screen
        name="QuoteDetail"
        component={QuoteDetailScreen}
        options={{title: 'Détails du devis'}}
      />
      <Stack.Screen
        name="CreateQuote"
        component={CreateQuoteScreen}
        options={{title: 'Envoyer un devis'}}
      />
      <Stack.Screen
        name="Chat"
        component={ChatScreen}
        options={({route}) => ({title: route.params?.name || 'Chat'})}
      />
      <Stack.Screen
        name="ArtisanDetail"
        component={ArtisanDetailScreen}
        options={{title: 'Profil artisan'}}
      />
      <Stack.Screen
        name="Notifications"
        component={NotificationsScreen}
        options={{title: 'Notifications'}}
      />
      <Stack.Screen
        name="Settings"
        component={SettingsScreen}
        options={{title: 'Paramètres'}}
      />
      <Stack.Screen
        name="Trades"
        component={TradesScreen}
        options={{title: 'Métiers & Services'}}
      />
      <Stack.Screen
        name="TradeDetail"
        component={TradeDetailScreen}
        options={{title: 'Détails du métier'}}
      />
      <Stack.Screen
        name="Favorites"
        component={FavoritesScreen}
        options={{title: 'Mes Favoris'}}
      />
      <Stack.Screen
        name="MarketPrices"
        component={MarketPricesScreen}
        options={{title: 'Prix du marché'}}
      />
      <Stack.Screen
        name="Help"
        component={HelpScreen}
        options={{title: 'Aide & FAQ'}}
      />
    </Stack.Navigator>
  );
}

// Root Navigator
function RootNavigator() {
  const {isAuthenticated, isLoading} = useAuth();

  if (isLoading) {
    return <SplashScreen />;
  }

  return (
    <NavigationContainer>
      {isAuthenticated ? <AppStack /> : <AuthStack />}
    </NavigationContainer>
  );
}

// Main App Component
export default function App() {
  return (
    <PaperProvider>
      <AuthProvider>
        <RootNavigator />
      </AuthProvider>
    </PaperProvider>
  );
}
