import React, {useState, useEffect} from 'react';
import {View, StyleSheet, ScrollView, Alert} from 'react-native';
import {
  Text,
  Surface,
  List,
  Switch,
  Divider,
  RadioButton,
  Button,
} from 'react-native-paper';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function SettingsScreen() {
  const [settings, setSettings] = useState({
    notifications: {
      enabled: true,
      newQuotes: true,
      quoteResponses: true,
      newMessages: true,
      projectUpdates: true,
      marketing: false,
    },
    language: 'fr',
    theme: 'light',
    autoUpdate: true,
  });

  const [languageDialogVisible, setLanguageDialogVisible] = useState(false);

  useEffect(() => {
    loadSettings();
  }, []);

  const loadSettings = async () => {
    try {
      const savedSettings = await AsyncStorage.getItem('appSettings');
      if (savedSettings) {
        setSettings(JSON.parse(savedSettings));
      }
    } catch (error) {
      console.error('Error loading settings:', error);
    }
  };

  const saveSettings = async newSettings => {
    try {
      await AsyncStorage.setItem('appSettings', JSON.stringify(newSettings));
      setSettings(newSettings);
    } catch (error) {
      console.error('Error saving settings:', error);
      Alert.alert('Erreur', 'Impossible de sauvegarder les paramètres');
    }
  };

  const toggleNotification = (key, value) => {
    const newSettings = {
      ...settings,
      notifications: {
        ...settings.notifications,
        [key]: value,
      },
    };
    saveSettings(newSettings);
  };

  const toggleSetting = (key, value) => {
    const newSettings = {
      ...settings,
      [key]: value,
    };
    saveSettings(newSettings);
  };

  const changeLanguage = language => {
    const newSettings = {
      ...settings,
      language,
    };
    saveSettings(newSettings);
    setLanguageDialogVisible(false);
    Alert.alert(
      'Langue modifiée',
      'Veuillez redémarrer l\'application pour appliquer les changements',
    );
  };

  const clearCache = async () => {
    Alert.alert(
      'Vider le cache',
      'Êtes-vous sûr de vouloir vider le cache de l\'application ?',
      [
        {text: 'Annuler', style: 'cancel'},
        {
          text: 'Confirmer',
          onPress: async () => {
            try {
              // Keep only essential data (token, user, settings)
              const token = await AsyncStorage.getItem('token');
              const user = await AsyncStorage.getItem('user');
              const appSettings = await AsyncStorage.getItem('appSettings');

              await AsyncStorage.clear();

              if (token) await AsyncStorage.setItem('token', token);
              if (user) await AsyncStorage.setItem('user', user);
              if (appSettings)
                await AsyncStorage.setItem('appSettings', appSettings);

              Alert.alert('Succès', 'Le cache a été vidé');
            } catch (error) {
              Alert.alert('Erreur', 'Impossible de vider le cache');
            }
          },
        },
      ],
    );
  };

  const getLanguageName = code => {
    const languages = {
      fr: 'Français',
      en: 'English',
      es: 'Español',
      de: 'Deutsch',
      it: 'Italiano',
    };
    return languages[code] || code;
  };

  return (
    <ScrollView style={styles.container}>
      {/* Notifications */}
      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Notifications</Text>

        <List.Item
          title="Activer les notifications"
          description="Recevoir toutes les notifications"
          left={props => <List.Icon {...props} icon="bell" />}
          right={() => (
            <Switch
              value={settings.notifications.enabled}
              onValueChange={value => toggleNotification('enabled', value)}
            />
          )}
        />
        <Divider />

        {settings.notifications.enabled && (
          <>
            <List.Item
              title="Nouveaux devis"
              description="Notification lors de la réception d'un devis"
              left={props => <List.Icon {...props} icon="file-document" />}
              right={() => (
                <Switch
                  value={settings.notifications.newQuotes}
                  onValueChange={value => toggleNotification('newQuotes', value)}
                />
              )}
            />
            <Divider />

            <List.Item
              title="Réponses aux devis"
              description="Notification quand un devis est accepté/refusé"
              left={props => <List.Icon {...props} icon="comment-check" />}
              right={() => (
                <Switch
                  value={settings.notifications.quoteResponses}
                  onValueChange={value =>
                    toggleNotification('quoteResponses', value)
                  }
                />
              )}
            />
            <Divider />

            <List.Item
              title="Nouveaux messages"
              description="Notification lors de la réception d'un message"
              left={props => <List.Icon {...props} icon="message" />}
              right={() => (
                <Switch
                  value={settings.notifications.newMessages}
                  onValueChange={value =>
                    toggleNotification('newMessages', value)
                  }
                />
              )}
            />
            <Divider />

            <List.Item
              title="Mises à jour de projets"
              description="Notification lors de changements sur vos projets"
              left={props => <List.Icon {...props} icon="briefcase" />}
              right={() => (
                <Switch
                  value={settings.notifications.projectUpdates}
                  onValueChange={value =>
                    toggleNotification('projectUpdates', value)
                  }
                />
              )}
            />
            <Divider />

            <List.Item
              title="Offres marketing"
              description="Recevoir des offres et promotions"
              left={props => <List.Icon {...props} icon="tag" />}
              right={() => (
                <Switch
                  value={settings.notifications.marketing}
                  onValueChange={value => toggleNotification('marketing', value)}
                />
              )}
            />
          </>
        )}
      </Surface>

      {/* Language & Region */}
      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Langue et région</Text>

        <List.Item
          title="Langue"
          description={getLanguageName(settings.language)}
          left={props => <List.Icon {...props} icon="translate" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={() => setLanguageDialogVisible(true)}
        />
      </Surface>

      {/* App Settings */}
      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Application</Text>

        <List.Item
          title="Mises à jour automatiques"
          description="Télécharger automatiquement les mises à jour"
          left={props => <List.Icon {...props} icon="download" />}
          right={() => (
            <Switch
              value={settings.autoUpdate}
              onValueChange={value => toggleSetting('autoUpdate', value)}
            />
          )}
        />
        <Divider />

        <List.Item
          title="Vider le cache"
          description="Libérer de l'espace de stockage"
          left={props => <List.Icon {...props} icon="delete-sweep" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          onPress={clearCache}
        />
      </Surface>

      {/* Data & Privacy */}
      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Données et confidentialité</Text>

        <List.Item
          title="Données collectées"
          description="Voir les données que nous collectons"
          left={props => <List.Icon {...props} icon="shield-lock" />}
          right={props => <List.Icon {...props} icon="chevron-right" />}
        />
        <Divider />

        <List.Item
          title="Supprimer mon compte"
          description="Suppression définitive"
          left={props => (
            <List.Icon {...props} icon="account-remove" color="#ef4444" />
          )}
          right={props => <List.Icon {...props} icon="chevron-right" />}
          titleStyle={{color: '#ef4444'}}
          onPress={() =>
            Alert.alert(
              'Supprimer le compte',
              'Cette action est irréversible. Veuillez contacter le support pour supprimer votre compte.',
            )
          }
        />
      </Surface>

      {/* Language Selection Dialog */}
      {languageDialogVisible && (
        <Surface style={styles.languageDialog}>
          <Text style={styles.dialogTitle}>Choisir la langue</Text>
          <Divider />

          <RadioButton.Group
            onValueChange={changeLanguage}
            value={settings.language}>
            <RadioButton.Item label="Français" value="fr" />
            <RadioButton.Item label="English" value="en" />
            <RadioButton.Item label="Español" value="es" />
            <RadioButton.Item label="Deutsch" value="de" />
            <RadioButton.Item label="Italiano" value="it" />
          </RadioButton.Group>

          <Button
            mode="text"
            onPress={() => setLanguageDialogVisible(false)}
            style={styles.dialogButton}>
            Annuler
          </Button>
        </Surface>
      )}
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  section: {
    marginHorizontal: 15,
    marginTop: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
    overflow: 'hidden',
  },
  sectionTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#6b7280',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    padding: 15,
    paddingBottom: 8,
  },
  languageDialog: {
    position: 'absolute',
    top: 100,
    left: 30,
    right: 30,
    borderRadius: 12,
    elevation: 8,
    backgroundColor: '#fff',
  },
  dialogTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    padding: 20,
    paddingBottom: 15,
  },
  dialogButton: {
    marginTop: 10,
  },
});
