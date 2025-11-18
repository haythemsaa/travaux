import React, {useState, useEffect} from 'react';
import {View, StyleSheet, ScrollView, Alert} from 'react-native';
import {
  Text,
  TextInput,
  Button,
  Surface,
  HelperText,
  Avatar,
} from 'react-native-paper';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

export default function EditProfileScreen({navigation}) {
  const {user, setUser} = useAuth();
  const [formData, setFormData] = useState({
    first_name: '',
    last_name: '',
    email: '',
    phone: '',
    current_password: '',
    new_password: '',
    confirm_password: '',
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [initialLoading, setInitialLoading] = useState(true);

  useEffect(() => {
    loadProfile();
  }, []);

  const loadProfile = async () => {
    try {
      const response = await apiService.getCurrentUser();
      if (response.success) {
        const userData = response.data.user;
        setFormData({
          first_name: userData.first_name || '',
          last_name: userData.last_name || '',
          email: userData.email || '',
          phone: userData.phone || '',
          current_password: '',
          new_password: '',
          confirm_password: '',
        });
      }
    } catch (error) {
      console.error('Error loading profile:', error);
    } finally {
      setInitialLoading(false);
    }
  };

  const handleChange = (field, value) => {
    setFormData(prev => ({...prev, [field]: value}));
    if (errors[field]) {
      setErrors(prev => ({...prev, [field]: ''}));
    }
  };

  const validateForm = () => {
    const newErrors = {};

    if (!formData.first_name || formData.first_name.trim().length < 2) {
      newErrors.first_name = 'Le prénom doit contenir au moins 2 caractères';
    }

    if (!formData.last_name || formData.last_name.trim().length < 2) {
      newErrors.last_name = 'Le nom doit contenir au moins 2 caractères';
    }

    if (!formData.email || !/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Email invalide';
    }

    if (formData.phone && formData.phone.length < 8) {
      newErrors.phone = 'Numéro de téléphone invalide';
    }

    // Password validation only if user wants to change it
    if (formData.new_password) {
      if (!formData.current_password) {
        newErrors.current_password = 'Mot de passe actuel requis';
      }

      if (formData.new_password.length < 6) {
        newErrors.new_password = 'Minimum 6 caractères';
      }

      if (formData.new_password !== formData.confirm_password) {
        newErrors.confirm_password = 'Les mots de passe ne correspondent pas';
      }
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleSubmit = async () => {
    if (!validateForm()) {
      Alert.alert('Erreur', 'Veuillez corriger les erreurs du formulaire');
      return;
    }

    setLoading(true);
    try {
      const updateData = {
        first_name: formData.first_name.trim(),
        last_name: formData.last_name.trim(),
        email: formData.email.trim(),
        phone: formData.phone.trim() || null,
      };

      // Add password change if provided
      if (formData.new_password) {
        updateData.current_password = formData.current_password;
        updateData.new_password = formData.new_password;
      }

      const response = await apiService.updateProfile(updateData);

      if (response.success) {
        // Update local user data
        setUser(prev => ({
          ...prev,
          first_name: updateData.first_name,
          last_name: updateData.last_name,
          email: updateData.email,
          phone: updateData.phone,
        }));

        Alert.alert('Succès', 'Profil mis à jour avec succès', [
          {
            text: 'OK',
            onPress: () => navigation.goBack(),
          },
        ]);
      }
    } catch (error) {
      console.error('Error updating profile:', error);
      Alert.alert(
        'Erreur',
        error.response?.data?.error || 'Impossible de mettre à jour le profil',
      );
    } finally {
      setLoading(false);
    }
  };

  const getInitials = () => {
    if (!formData.first_name && !formData.last_name) return 'U';
    return (
      (formData.first_name?.[0] || '') + (formData.last_name?.[0] || '')
    ).toUpperCase();
  };

  if (initialLoading) {
    return (
      <View style={styles.loadingContainer}>
        <Text>Chargement...</Text>
      </View>
    );
  }

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Surface style={styles.avatarSection}>
        <Avatar.Text size={80} label={getInitials()} style={styles.avatar} />
        <Text style={styles.avatarHint}>
          Appuyez pour changer la photo (bientôt disponible)
        </Text>
      </Surface>

      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Informations personnelles</Text>

        <TextInput
          label="Prénom *"
          value={formData.first_name}
          onChangeText={value => handleChange('first_name', value)}
          mode="outlined"
          error={!!errors.first_name}
        />
        <HelperText type="error" visible={!!errors.first_name}>
          {errors.first_name}
        </HelperText>

        <TextInput
          label="Nom *"
          value={formData.last_name}
          onChangeText={value => handleChange('last_name', value)}
          mode="outlined"
          error={!!errors.last_name}
        />
        <HelperText type="error" visible={!!errors.last_name}>
          {errors.last_name}
        </HelperText>

        <TextInput
          label="Email *"
          value={formData.email}
          onChangeText={value => handleChange('email', value)}
          mode="outlined"
          keyboardType="email-address"
          autoCapitalize="none"
          error={!!errors.email}
        />
        <HelperText type="error" visible={!!errors.email}>
          {errors.email}
        </HelperText>

        <TextInput
          label="Téléphone"
          value={formData.phone}
          onChangeText={value => handleChange('phone', value)}
          mode="outlined"
          keyboardType="phone-pad"
          error={!!errors.phone}
          left={<TextInput.Icon icon="phone" />}
        />
        <HelperText type="error" visible={!!errors.phone}>
          {errors.phone}
        </HelperText>
      </Surface>

      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Changer le mot de passe</Text>
        <Text style={styles.sectionHint}>
          Laissez vide si vous ne souhaitez pas changer votre mot de passe
        </Text>

        <TextInput
          label="Mot de passe actuel"
          value={formData.current_password}
          onChangeText={value => handleChange('current_password', value)}
          mode="outlined"
          secureTextEntry
          error={!!errors.current_password}
          left={<TextInput.Icon icon="lock" />}
        />
        <HelperText type="error" visible={!!errors.current_password}>
          {errors.current_password}
        </HelperText>

        <TextInput
          label="Nouveau mot de passe"
          value={formData.new_password}
          onChangeText={value => handleChange('new_password', value)}
          mode="outlined"
          secureTextEntry
          error={!!errors.new_password}
          left={<TextInput.Icon icon="lock-outline" />}
        />
        <HelperText type="error" visible={!!errors.new_password}>
          {errors.new_password}
        </HelperText>

        <TextInput
          label="Confirmer le nouveau mot de passe"
          value={formData.confirm_password}
          onChangeText={value => handleChange('confirm_password', value)}
          mode="outlined"
          secureTextEntry
          error={!!errors.confirm_password}
          left={<TextInput.Icon icon="lock-check" />}
        />
        <HelperText type="error" visible={!!errors.confirm_password}>
          {errors.confirm_password}
        </HelperText>
      </Surface>

      <View style={styles.footer}>
        <Button
          mode="contained"
          onPress={handleSubmit}
          loading={loading}
          disabled={loading}
          style={styles.submitButton}
          buttonColor="#3b82f6">
          Enregistrer les modifications
        </Button>
      </View>
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
  content: {
    padding: 15,
  },
  avatarSection: {
    alignItems: 'center',
    padding: 30,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  avatar: {
    backgroundColor: '#3b82f6',
    marginBottom: 10,
  },
  avatarHint: {
    fontSize: 12,
    color: '#9ca3af',
    fontStyle: 'italic',
  },
  section: {
    padding: 20,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 8,
  },
  sectionHint: {
    fontSize: 13,
    color: '#6b7280',
    marginBottom: 15,
    fontStyle: 'italic',
  },
  footer: {
    marginTop: 10,
    marginBottom: 30,
  },
  submitButton: {
    paddingVertical: 6,
  },
});
