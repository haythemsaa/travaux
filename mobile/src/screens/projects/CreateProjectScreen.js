import React, {useState, useEffect} from 'react';
import {View, StyleSheet, ScrollView, Alert} from 'react-native';
import {
  Text,
  TextInput,
  Button,
  Surface,
  HelperText,
  Menu,
  Divider,
} from 'react-native-paper';
import apiService from '../../services/api';

export default function CreateProjectScreen({navigation}) {
  const [formData, setFormData] = useState({
    title: '',
    description: '',
    category_id: '',
    budget_min: '',
    budget_max: '',
    currency_code: 'EUR',
    address: '',
    postal_code: '',
    city: '',
    country_code: 'FR',
    preferred_date: '',
    urgency: 'normal',
  });
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [categories, setCategories] = useState([]);
  const [categoryMenuVisible, setCategoryMenuVisible] = useState(false);
  const [urgencyMenuVisible, setUrgencyMenuVisible] = useState(false);

  useEffect(() => {
    loadCategories();
  }, []);

  const loadCategories = async () => {
    try {
      const response = await apiService.getCategories();
      if (response.success) {
        setCategories(response.data.categories);
      }
    } catch (error) {
      console.error('Error loading categories:', error);
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

    if (!formData.title || formData.title.trim().length < 5) {
      newErrors.title = 'Le titre doit contenir au moins 5 caractères';
    }

    if (!formData.description || formData.description.trim().length < 20) {
      newErrors.description = 'La description doit contenir au moins 20 caractères';
    }

    if (!formData.category_id) {
      newErrors.category_id = 'Veuillez sélectionner une catégorie';
    }

    if (!formData.budget_min || parseFloat(formData.budget_min) <= 0) {
      newErrors.budget_min = 'Budget minimum requis';
    }

    if (!formData.budget_max || parseFloat(formData.budget_max) <= 0) {
      newErrors.budget_max = 'Budget maximum requis';
    }

    if (
      formData.budget_min &&
      formData.budget_max &&
      parseFloat(formData.budget_min) > parseFloat(formData.budget_max)
    ) {
      newErrors.budget_max = 'Le budget max doit être supérieur au min';
    }

    if (!formData.address || formData.address.trim().length < 5) {
      newErrors.address = 'Adresse requise';
    }

    if (!formData.postal_code || formData.postal_code.trim().length < 3) {
      newErrors.postal_code = 'Code postal requis';
    }

    if (!formData.city || formData.city.trim().length < 2) {
      newErrors.city = 'Ville requise';
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
      const projectData = {
        title: formData.title.trim(),
        description: formData.description.trim(),
        category_id: parseInt(formData.category_id),
        budget_min: parseFloat(formData.budget_min),
        budget_max: parseFloat(formData.budget_max),
        currency_code: formData.currency_code,
        address: formData.address.trim(),
        postal_code: formData.postal_code.trim(),
        city: formData.city.trim(),
        country_code: formData.country_code,
        urgency: formData.urgency,
      };

      if (formData.preferred_date) {
        projectData.preferred_date = formData.preferred_date;
      }

      const response = await apiService.createProject(projectData);

      if (response.success) {
        Alert.alert('Succès', 'Votre projet a été créé avec succès', [
          {
            text: 'OK',
            onPress: () => navigation.goBack(),
          },
        ]);
      }
    } catch (error) {
      console.error('Error creating project:', error);
      Alert.alert(
        'Erreur',
        error.response?.data?.error || 'Impossible de créer le projet',
      );
    } finally {
      setLoading(false);
    }
  };

  const getCategoryName = () => {
    const category = categories.find(c => c.id == formData.category_id);
    return category ? category.name : 'Sélectionner...';
  };

  const getUrgencyLabel = () => {
    const labels = {
      urgent: 'Urgent (< 1 semaine)',
      normal: 'Normal (1-4 semaines)',
      flexible: 'Flexible (> 1 mois)',
    };
    return labels[formData.urgency] || 'Sélectionner...';
  };

  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Informations du projet</Text>

        <TextInput
          label="Titre du projet *"
          value={formData.title}
          onChangeText={value => handleChange('title', value)}
          mode="outlined"
          placeholder="Ex: Rénovation cuisine complète"
          error={!!errors.title}
        />
        <HelperText type="error" visible={!!errors.title}>
          {errors.title}
        </HelperText>

        <TextInput
          label="Description *"
          value={formData.description}
          onChangeText={value => handleChange('description', value)}
          mode="outlined"
          multiline
          numberOfLines={6}
          placeholder="Décrivez votre projet en détail..."
          style={styles.textarea}
          error={!!errors.description}
        />
        <HelperText type="error" visible={!!errors.description}>
          {errors.description}
        </HelperText>

        <Menu
          visible={categoryMenuVisible}
          onDismiss={() => setCategoryMenuVisible(false)}
          anchor={
            <Button
              mode="outlined"
              onPress={() => setCategoryMenuVisible(true)}
              style={[styles.menuButton, errors.category_id && styles.errorButton]}
              contentStyle={styles.menuButtonContent}>
              {getCategoryName()}
            </Button>
          }>
          {categories.map(category => (
            <Menu.Item
              key={category.id}
              onPress={() => {
                handleChange('category_id', category.id);
                setCategoryMenuVisible(false);
              }}
              title={category.name}
            />
          ))}
        </Menu>
        <HelperText type="error" visible={!!errors.category_id}>
          {errors.category_id}
        </HelperText>
      </Surface>

      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Budget</Text>

        <View style={styles.row}>
          <View style={styles.halfWidth}>
            <TextInput
              label="Budget minimum *"
              value={formData.budget_min}
              onChangeText={value => handleChange('budget_min', value)}
              mode="outlined"
              keyboardType="decimal-pad"
              left={<TextInput.Icon icon="currency-eur" />}
              error={!!errors.budget_min}
            />
            <HelperText type="error" visible={!!errors.budget_min}>
              {errors.budget_min}
            </HelperText>
          </View>

          <View style={styles.halfWidth}>
            <TextInput
              label="Budget maximum *"
              value={formData.budget_max}
              onChangeText={value => handleChange('budget_max', value)}
              mode="outlined"
              keyboardType="decimal-pad"
              left={<TextInput.Icon icon="currency-eur" />}
              error={!!errors.budget_max}
            />
            <HelperText type="error" visible={!!errors.budget_max}>
              {errors.budget_max}
            </HelperText>
          </View>
        </View>
      </Surface>

      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Localisation</Text>

        <TextInput
          label="Adresse *"
          value={formData.address}
          onChangeText={value => handleChange('address', value)}
          mode="outlined"
          placeholder="123 Rue Example"
          error={!!errors.address}
        />
        <HelperText type="error" visible={!!errors.address}>
          {errors.address}
        </HelperText>

        <View style={styles.row}>
          <View style={styles.halfWidth}>
            <TextInput
              label="Code postal *"
              value={formData.postal_code}
              onChangeText={value => handleChange('postal_code', value)}
              mode="outlined"
              keyboardType="number-pad"
              error={!!errors.postal_code}
            />
            <HelperText type="error" visible={!!errors.postal_code}>
              {errors.postal_code}
            </HelperText>
          </View>

          <View style={styles.halfWidth}>
            <TextInput
              label="Ville *"
              value={formData.city}
              onChangeText={value => handleChange('city', value)}
              mode="outlined"
              error={!!errors.city}
            />
            <HelperText type="error" visible={!!errors.city}>
              {errors.city}
            </HelperText>
          </View>
        </View>
      </Surface>

      <Surface style={styles.section}>
        <Text style={styles.sectionTitle}>Détails</Text>

        <TextInput
          label="Date souhaitée (optionnel)"
          value={formData.preferred_date}
          onChangeText={value => handleChange('preferred_date', value)}
          mode="outlined"
          placeholder="YYYY-MM-DD (ex: 2024-03-15)"
          left={<TextInput.Icon icon="calendar" />}
        />

        <Menu
          visible={urgencyMenuVisible}
          onDismiss={() => setUrgencyMenuVisible(false)}
          anchor={
            <Button
              mode="outlined"
              onPress={() => setUrgencyMenuVisible(true)}
              style={styles.menuButton}
              contentStyle={styles.menuButtonContent}>
              {getUrgencyLabel()}
            </Button>
          }>
          <Menu.Item
            onPress={() => {
              handleChange('urgency', 'urgent');
              setUrgencyMenuVisible(false);
            }}
            title="Urgent (< 1 semaine)"
          />
          <Menu.Item
            onPress={() => {
              handleChange('urgency', 'normal');
              setUrgencyMenuVisible(false);
            }}
            title="Normal (1-4 semaines)"
          />
          <Menu.Item
            onPress={() => {
              handleChange('urgency', 'flexible');
              setUrgencyMenuVisible(false);
            }}
            title="Flexible (> 1 mois)"
          />
        </Menu>
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
          Créer le projet
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
    marginBottom: 15,
  },
  textarea: {
    minHeight: 120,
  },
  menuButton: {
    marginTop: 8,
  },
  menuButtonContent: {
    justifyContent: 'flex-start',
  },
  errorButton: {
    borderColor: '#ef4444',
  },
  row: {
    flexDirection: 'row',
    gap: 10,
  },
  halfWidth: {
    flex: 1,
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
