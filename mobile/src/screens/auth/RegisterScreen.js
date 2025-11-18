import React, {useState} from 'react';
import {
  View,
  StyleSheet,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
  Alert,
} from 'react-native';
import {
  TextInput,
  Button,
  Title,
  Text,
  HelperText,
  SegmentedButtons,
} from 'react-native-paper';
import {useAuth} from '../../context/AuthContext';

export default function RegisterScreen({navigation}) {
  const {register} = useAuth();
  const [formData, setFormData] = useState({
    email: '',
    password: '',
    password_confirm: '',
    first_name: '',
    last_name: '',
    phone: '',
    role: 'client',
    country_code: 'FR',
  });
  const [loading, setLoading] = useState(false);
  const [errors, setErrors] = useState({});
  const [showPassword, setShowPassword] = useState(false);

  const handleChange = (field, value) => {
    setFormData({...formData, [field]: value});
    // Clear error when user types
    if (errors[field]) {
      setErrors({...errors, [field]: null});
    }
  };

  const validate = () => {
    const newErrors = {};

    if (!formData.first_name) {
      newErrors.first_name = 'Prénom requis';
    }

    if (!formData.last_name) {
      newErrors.last_name = 'Nom requis';
    }

    if (!formData.email) {
      newErrors.email = 'Email requis';
    } else if (!/\S+@\S+\.\S+/.test(formData.email)) {
      newErrors.email = 'Email invalide';
    }

    if (!formData.phone) {
      newErrors.phone = 'Téléphone requis';
    }

    if (!formData.password) {
      newErrors.password = 'Mot de passe requis';
    } else if (formData.password.length < 6) {
      newErrors.password = 'Minimum 6 caractères';
    }

    if (formData.password !== formData.password_confirm) {
      newErrors.password_confirm = 'Les mots de passe ne correspondent pas';
    }

    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };

  const handleRegister = async () => {
    if (!validate()) {
      return;
    }

    setLoading(true);

    try {
      const result = await register(formData);

      if (result.success) {
        Alert.alert(
          'Inscription réussie',
          'Bienvenue sur Travaux Pro!',
          [{text: 'OK'}]
        );
      } else {
        Alert.alert('Erreur', result.error || 'Inscription échouée');
      }
    } catch (error) {
      Alert.alert('Erreur', 'Une erreur est survenue. Veuillez réessayer.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <KeyboardAvoidingView
      style={styles.container}
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        <View style={styles.header}>
          <Title style={styles.title}>Créer un compte</Title>
          <Text style={styles.subtitle}>
            Rejoignez Travaux Pro dès aujourd'hui
          </Text>
        </View>

        <View style={styles.form}>
          {/* Role Selection */}
          <Text style={styles.label}>Je suis :</Text>
          <SegmentedButtons
            value={formData.role}
            onValueChange={value => handleChange('role', value)}
            buttons={[
              {
                value: 'client',
                label: 'Un particulier',
                icon: 'account',
              },
              {
                value: 'artisan',
                label: 'Un professionnel',
                icon: 'account-hard-hat',
              },
            ]}
            style={styles.segmentedButtons}
          />

          {/* First Name */}
          <TextInput
            label="Prénom *"
            value={formData.first_name}
            onChangeText={value => handleChange('first_name', value)}
            mode="outlined"
            error={!!errors.first_name}
            left={<TextInput.Icon icon="account" />}
          />
          <HelperText type="error" visible={!!errors.first_name}>
            {errors.first_name}
          </HelperText>

          {/* Last Name */}
          <TextInput
            label="Nom *"
            value={formData.last_name}
            onChangeText={value => handleChange('last_name', value)}
            mode="outlined"
            error={!!errors.last_name}
            left={<TextInput.Icon icon="account" />}
          />
          <HelperText type="error" visible={!!errors.last_name}>
            {errors.last_name}
          </HelperText>

          {/* Email */}
          <TextInput
            label="Email *"
            value={formData.email}
            onChangeText={value => handleChange('email', value)}
            keyboardType="email-address"
            autoCapitalize="none"
            mode="outlined"
            error={!!errors.email}
            left={<TextInput.Icon icon="email" />}
          />
          <HelperText type="error" visible={!!errors.email}>
            {errors.email}
          </HelperText>

          {/* Phone */}
          <TextInput
            label="Téléphone *"
            value={formData.phone}
            onChangeText={value => handleChange('phone', value)}
            keyboardType="phone-pad"
            mode="outlined"
            error={!!errors.phone}
            left={<TextInput.Icon icon="phone" />}
          />
          <HelperText type="error" visible={!!errors.phone}>
            {errors.phone}
          </HelperText>

          {/* Password */}
          <TextInput
            label="Mot de passe *"
            value={formData.password}
            onChangeText={value => handleChange('password', value)}
            secureTextEntry={!showPassword}
            mode="outlined"
            error={!!errors.password}
            left={<TextInput.Icon icon="lock" />}
            right={
              <TextInput.Icon
                icon={showPassword ? 'eye-off' : 'eye'}
                onPress={() => setShowPassword(!showPassword)}
              />
            }
          />
          <HelperText type="error" visible={!!errors.password}>
            {errors.password}
          </HelperText>

          {/* Password Confirmation */}
          <TextInput
            label="Confirmer mot de passe *"
            value={formData.password_confirm}
            onChangeText={value => handleChange('password_confirm', value)}
            secureTextEntry={!showPassword}
            mode="outlined"
            error={!!errors.password_confirm}
            left={<TextInput.Icon icon="lock-check" />}
          />
          <HelperText type="error" visible={!!errors.password_confirm}>
            {errors.password_confirm}
          </HelperText>

          {/* Register Button */}
          <Button
            mode="contained"
            onPress={handleRegister}
            loading={loading}
            disabled={loading}
            style={styles.button}>
            S'inscrire
          </Button>

          {/* Login Link */}
          <View style={styles.footer}>
            <Text>Déjà un compte ? </Text>
            <Button mode="text" onPress={() => navigation.navigate('Login')}>
              Se connecter
            </Button>
          </View>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f5f5f5',
  },
  scrollContent: {
    flexGrow: 1,
    padding: 20,
  },
  header: {
    alignItems: 'center',
    marginBottom: 30,
    marginTop: 20,
  },
  title: {
    fontSize: 28,
    fontWeight: 'bold',
    color: '#3b82f6',
    marginBottom: 10,
  },
  subtitle: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
  },
  form: {
    width: '100%',
  },
  label: {
    fontSize: 16,
    fontWeight: '500',
    marginBottom: 10,
    color: '#374151',
  },
  segmentedButtons: {
    marginBottom: 20,
  },
  button: {
    marginTop: 20,
    paddingVertical: 8,
  },
  footer: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: 20,
  },
});
