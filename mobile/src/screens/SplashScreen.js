import React from 'react';
import {View, StyleSheet, Image, ActivityIndicator} from 'react-native';
import {Text} from 'react-native-paper';

export default function SplashScreen() {
  return (
    <View style={styles.container}>
      <Image
        source={require('../assets/logo.png')}
        style={styles.logo}
        resizeMode="contain"
      />
      <Text style={styles.title}>Travaux Pro</Text>
      <ActivityIndicator size="large" color="#3b82f6" style={styles.loader} />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  logo: {
    width: 150,
    height: 150,
    marginBottom: 20,
  },
  title: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#3b82f6',
    marginBottom: 40,
  },
  loader: {
    marginTop: 20,
  },
});
