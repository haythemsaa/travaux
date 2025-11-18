import React, {useState} from 'react';
import {View, StyleSheet, ScrollView, Alert} from 'react-native';
import {
  Text,
  TextInput,
  Button,
  Surface,
  HelperText,
} from 'react-native-paper';
import apiService from '../../services/api';

export default function CreateQuoteScreen({route, navigation}) {
  const {projectId, projectTitle} = route.params;
  const [formData, setFormData] = useState({
    amount: '',
    currency_code: 'EUR',
    description: '',
    estimated_duration: '',
    start_date: '',
    payment_terms: '',
    valid_until: '',
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);

  const handleChange = (field, value) => {
    setFormData(prev => ({...prev, [field]: value}));
    // Clear error when user starts typing
    if (errors[field]) {
      setErrors(prev => ({...prev, [field]: ''}));
    }
  };

  const validateForm = () => {
    const newErrors = {};

    if (!formData.amount || parseFloat(formData.amount) <= 0) {
      newErrors.amount = 'Le montant est requis et doit être positif';
    }

    if (!formData.description || formData.description.trim().length < 20) {
      newErrors.description =
        'La description doit contenir au moins 20 caractères';
    }

    if (!formData.estimated_duration || formData.estimated_duration.trim().length < 3) {
      newErrors.estimated_duration = 'La durée estimée est requise';
    }

    if (!formData.start_date) {
      newErrors.start_date = 'La date de début est requise (format: YYYY-MM-DD)';
    }

    if (!formData.valid_until) {
      newErrors.valid_until = 'La date de validité est requise (format: YYYY-MM-DD)';
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
      const quoteData = {
        project_id: projectId,
        amount: parseFloat(formData.amount),
        currency_code: formData.currency_code,
        description: formData.description.trim(),
        estimated_duration: formData.estimated_duration.trim(),
        start_date: formData.start_date,
        payment_terms: formData.payment_terms.trim() || null,
        valid_until: formData.valid_until,
      };

      const response = await apiService.createQuote(quoteData);

      if (response.success) {
        Alert.alert(
          'Succès',
          'Votre devis a été envoyé avec succès',
          [
            {
              text: 'OK',
              onPress: () => navigation.goBack(),
            },
          ],
        );
      }
    } catch (error) {
      console.error('Error creating quote:', error);
      Alert.alert(
        'Erreur',
        error.response?.data?.error || 'Impossible d\'envoyer le devis',
      );
    } finally {
      setLoading(false);
    }
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Surface style={styles.projectInfo}>
        <Text style={styles.label}>Projet</Text>
        <Text style={styles.projectTitle}>{projectTitle}</Text>
      </Surface>

      <Surface style={styles.section}>
        <TextInput
          label="Montant (€) *"
          value={formData.amount}
          onChangeText={value => handleChange('amount', value)}
          keyboardType="decimal-pad"
          mode="outlined"
          error={!!errors.amount}
          left={<TextInput.Icon icon="currency-eur" />}
        />
        <HelperText type="error" visible={!!errors.amount}>
          {errors.amount}
        </HelperText>

        <TextInput
          label="Description du devis *"
          value={formData.description}
          onChangeText={value => handleChange('description', value)}
          mode="outlined"
          multiline
          numberOfLines={6}
          placeholder="Décrivez en détail les travaux proposés, les matériaux utilisés, etc."
          style={styles.textarea}
          error={!!errors.description}
        />
        <HelperText type="error" visible={!!errors.description}>
          {errors.description}
        </HelperText>

        <TextInput
          label="Durée estimée *"
          value={formData.estimated_duration}
          onChangeText={value => handleChange('estimated_duration', value)}
          mode="outlined"
          placeholder="Ex: 2 semaines, 3 jours, 1 mois"
          error={!!errors.estimated_duration}
          left={<TextInput.Icon icon="clock-outline" />}
        />
        <HelperText type="error" visible={!!errors.estimated_duration}>
          {errors.estimated_duration}
        </HelperText>

        <TextInput
          label="Date de début souhaitée *"
          value={formData.start_date}
          onChangeText={value => handleChange('start_date', value)}
          mode="outlined"
          placeholder="YYYY-MM-DD (ex: 2024-03-15)"
          error={!!errors.start_date}
          left={<TextInput.Icon icon="calendar-start" />}
        />
        <HelperText type="error" visible={!!errors.start_date}>
          {errors.start_date}
        </HelperText>

        <TextInput
          label="Conditions de paiement"
          value={formData.payment_terms}
          onChangeText={value => handleChange('payment_terms', value)}
          mode="outlined"
          placeholder="Ex: 30% à la commande, 40% en cours, 30% à la fin"
          left={<TextInput.Icon icon="cash-multiple" />}
        />

        <TextInput
          label="Devis valide jusqu'au *"
          value={formData.valid_until}
          onChangeText={value => handleChange('valid_until', value)}
          mode="outlined"
          placeholder="YYYY-MM-DD (ex: 2024-04-15)"
          error={!!errors.valid_until}
          left={<TextInput.Icon icon="timer-sand" />}
        />
        <HelperText type="error" visible={!!errors.valid_until}>
          {errors.valid_until}
        </HelperText>
      </Surface>

      <View style={styles.footer}>
        <Text style={styles.footerText}>* Champs obligatoires</Text>
        <Button
          mode="contained"
          onPress={handleSubmit}
          loading={loading}
          disabled={loading}
          style={styles.submitButton}
          buttonColor="#3b82f6">
          Envoyer le devis
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
  content: {
    padding: 15,
  },
  projectInfo: {
    padding: 15,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  label: {
    fontSize: 12,
    fontWeight: '600',
    color: '#6b7280',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginBottom: 8,
  },
  projectTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#111827',
  },
  section: {
    padding: 20,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  textarea: {
    minHeight: 120,
  },
  footer: {
    marginTop: 10,
    marginBottom: 30,
  },
  footerText: {
    fontSize: 12,
    color: '#6b7280',
    fontStyle: 'italic',
    marginBottom: 15,
    textAlign: 'center',
  },
  submitButton: {
    paddingVertical: 6,
  },
});
