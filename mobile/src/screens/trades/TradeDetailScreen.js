import React, {useState, useEffect} from 'react';
import {
  View,
  StyleSheet,
  ScrollView,
  TouchableOpacity,
  Alert,
} from 'react-native';
import {
  Text,
  Card,
  ActivityIndicator,
  Surface,
  Chip,
  Divider,
  Button,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';

export default function TradeDetailScreen({route, navigation}) {
  const {trade} = route.params;
  const [customFields, setCustomFields] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    loadCustomFields();
  }, []);

  const loadCustomFields = async () => {
    try {
      const response = await apiService.getTradeFields(trade.id);
      if (response.success) {
        setCustomFields(response.data.fields || []);
      }
    } catch (error) {
      console.error('Error loading custom fields:', error);
    } finally {
      setLoading(false);
    }
  };

  const getFieldTypeIcon = type => {
    const iconMap = {
      text: 'form-textbox',
      textarea: 'text-box-outline',
      number: 'numeric',
      select: 'form-dropdown',
      radio: 'radiobox-marked',
      checkbox: 'checkbox-marked',
      date: 'calendar',
      email: 'email-outline',
    };
    return iconMap[type] || 'form-textbox';
  };

  const getFieldTypeLabel = type => {
    const labelMap = {
      text: 'Texte',
      textarea: 'Texte long',
      number: 'Nombre',
      select: 'Sélection',
      radio: 'Choix unique',
      checkbox: 'Choix multiple',
      date: 'Date',
      email: 'Email',
    };
    return labelMap[type] || type;
  };

  const parseOptions = optionsJson => {
    try {
      const parsed = JSON.parse(optionsJson);
      return parsed.options || [];
    } catch {
      return [];
    }
  };

  const handleCreateProject = () => {
    navigation.navigate('CreateProject', {
      preselectedTrade: trade,
    });
  };

  const renderFieldCard = field => {
    const options = field.options ? parseOptions(field.options) : [];

    return (
      <Card key={field.id} style={styles.fieldCard}>
        <Card.Content>
          <View style={styles.fieldHeader}>
            <View style={styles.fieldIconContainer}>
              <Icon
                name={getFieldTypeIcon(field.field_type)}
                size={20}
                color="#3b82f6"
              />
            </View>
            <View style={styles.fieldInfo}>
              <Text style={styles.fieldLabel}>
                {field.label_fr}
                {field.is_required ? (
                  <Text style={styles.required}> *</Text>
                ) : null}
              </Text>
              {field.label_en && (
                <Text style={styles.fieldLabelEn}>{field.label_en}</Text>
              )}
            </View>
            <Chip
              mode="outlined"
              style={styles.typeChip}
              textStyle={styles.typeChipText}>
              {getFieldTypeLabel(field.field_type)}
            </Chip>
          </View>

          {field.help_text && (
            <Text style={styles.helpText}>{field.help_text}</Text>
          )}

          {field.placeholder && (
            <Text style={styles.placeholder}>
              Exemple : {field.placeholder}
            </Text>
          )}

          {options.length > 0 && (
            <View style={styles.optionsContainer}>
              <Text style={styles.optionsLabel}>Options :</Text>
              {options.map((option, index) => (
                <View key={index} style={styles.optionItem}>
                  <Icon
                    name={
                      field.field_type === 'checkbox'
                        ? 'checkbox-blank-outline'
                        : 'radiobox-blank'
                    }
                    size={16}
                    color="#6b7280"
                  />
                  <Text style={styles.optionText}>{option}</Text>
                </View>
              ))}
            </View>
          )}

          {field.validation_rules && (
            <View style={styles.validationContainer}>
              <Icon name="information-outline" size={14} color="#6b7280" />
              <Text style={styles.validationText}>
                {JSON.stringify(field.validation_rules)}
              </Text>
            </View>
          )}
        </Card.Content>
      </Card>
    );
  };

  if (loading) {
    return (
      <View style={styles.centered}>
        <ActivityIndicator size="large" color="#3b82f6" />
        <Text style={styles.loadingText}>
          Chargement du formulaire...
        </Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        {/* Header */}
        <Surface style={styles.headerCard}>
          <View style={styles.headerContent}>
            <View style={styles.headerIconContainer}>
              <Icon name="wrench" size={40} color="#3b82f6" />
            </View>
            <View style={styles.headerInfo}>
              <Text style={styles.headerTitle}>{trade.name_fr}</Text>
              {trade.name_en && (
                <Text style={styles.headerSubtitle}>{trade.name_en}</Text>
              )}
              {trade.name_es && (
                <Text style={styles.headerSubtitle}>{trade.name_es}</Text>
              )}
            </View>
          </View>

          <View style={styles.statsRow}>
            <View style={styles.statItem}>
              <Icon name="form-select" size={20} color="#3b82f6" />
              <Text style={styles.statText}>
                {customFields.length} questions
              </Text>
            </View>
            <View style={styles.statItem}>
              <Icon name="translate" size={20} color="#10b981" />
              <Text style={styles.statText}>5 langues</Text>
            </View>
          </View>
        </Surface>

        {/* Description */}
        <Card style={styles.descriptionCard}>
          <Card.Content>
            <View style={styles.descriptionHeader}>
              <Icon name="information" size={24} color="#3b82f6" />
              <Text style={styles.descriptionTitle}>
                À propos de ce métier
              </Text>
            </View>
            <Text style={styles.descriptionText}>
              Ce formulaire personnalisé vous permet de décrire précisément
              votre projet concernant {trade.name_fr.toLowerCase()}. Les
              artisans recevront toutes les informations nécessaires pour vous
              fournir un devis adapté.
            </Text>
          </Card.Content>
        </Card>

        {/* Custom Fields */}
        {customFields.length > 0 ? (
          <>
            <View style={styles.sectionHeader}>
              <Text style={styles.sectionTitle}>
                Questionnaire personnalisé
              </Text>
              <Chip
                icon="help-circle"
                style={styles.requiredChip}
                textStyle={styles.requiredChipText}>
                * = Obligatoire
              </Chip>
            </View>

            {customFields.map(renderFieldCard)}
          </>
        ) : (
          <Card style={styles.emptyCard}>
            <Card.Content>
              <View style={styles.emptyContainer}>
                <Icon name="alert-circle-outline" size={48} color="#9ca3af" />
                <Text style={styles.emptyText}>
                  Aucune question spécifique
                </Text>
                <Text style={styles.emptySubtext}>
                  Vous pouvez directement créer un projet pour ce métier
                </Text>
              </View>
            </Card.Content>
          </Card>
        )}

        {/* Action Button */}
        <Button
          mode="contained"
          icon="plus"
          onPress={handleCreateProject}
          style={styles.actionButton}
          contentStyle={styles.actionButtonContent}>
          Créer un projet pour ce métier
        </Button>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  centered: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#f3f4f6',
  },
  loadingText: {
    marginTop: 16,
    fontSize: 16,
    color: '#6b7280',
  },
  scrollContent: {
    padding: 16,
    paddingBottom: 32,
  },
  headerCard: {
    padding: 16,
    borderRadius: 12,
    elevation: 2,
    marginBottom: 16,
  },
  headerContent: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 16,
  },
  headerIconContainer: {
    width: 64,
    height: 64,
    borderRadius: 32,
    backgroundColor: '#eff6ff',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 16,
  },
  headerInfo: {
    flex: 1,
  },
  headerTitle: {
    fontSize: 22,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 4,
  },
  headerSubtitle: {
    fontSize: 14,
    color: '#6b7280',
  },
  statsRow: {
    flexDirection: 'row',
    gap: 16,
  },
  statItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
  },
  statText: {
    fontSize: 14,
    color: '#374151',
    fontWeight: '500',
  },
  descriptionCard: {
    marginBottom: 16,
    elevation: 1,
  },
  descriptionHeader: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 12,
    gap: 8,
  },
  descriptionTitle: {
    fontSize: 16,
    fontWeight: '600',
    color: '#111827',
  },
  descriptionText: {
    fontSize: 14,
    color: '#4b5563',
    lineHeight: 20,
  },
  sectionHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 12,
  },
  sectionTitle: {
    fontSize: 18,
    fontWeight: '600',
    color: '#111827',
  },
  requiredChip: {
    backgroundColor: '#fef3c7',
  },
  requiredChipText: {
    color: '#92400e',
    fontSize: 11,
  },
  fieldCard: {
    marginBottom: 12,
    elevation: 1,
  },
  fieldHeader: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    marginBottom: 8,
  },
  fieldIconContainer: {
    width: 32,
    height: 32,
    borderRadius: 16,
    backgroundColor: '#eff6ff',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  fieldInfo: {
    flex: 1,
  },
  fieldLabel: {
    fontSize: 15,
    fontWeight: '600',
    color: '#111827',
    marginBottom: 2,
  },
  required: {
    color: '#ef4444',
  },
  fieldLabelEn: {
    fontSize: 12,
    color: '#6b7280',
  },
  typeChip: {
    height: 24,
    backgroundColor: '#f3f4f6',
    borderColor: '#d1d5db',
  },
  typeChipText: {
    fontSize: 10,
    color: '#4b5563',
  },
  helpText: {
    fontSize: 13,
    color: '#6b7280',
    marginTop: 4,
    marginLeft: 44,
    fontStyle: 'italic',
  },
  placeholder: {
    fontSize: 12,
    color: '#9ca3af',
    marginTop: 4,
    marginLeft: 44,
  },
  optionsContainer: {
    marginTop: 12,
    marginLeft: 44,
    padding: 12,
    backgroundColor: '#f9fafb',
    borderRadius: 8,
    borderWidth: 1,
    borderColor: '#e5e7eb',
  },
  optionsLabel: {
    fontSize: 12,
    fontWeight: '600',
    color: '#4b5563',
    marginBottom: 8,
  },
  optionItem: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 8,
    marginBottom: 6,
  },
  optionText: {
    fontSize: 13,
    color: '#374151',
  },
  validationContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    gap: 6,
    marginTop: 8,
    marginLeft: 44,
  },
  validationText: {
    fontSize: 11,
    color: '#6b7280',
  },
  emptyCard: {
    marginVertical: 16,
  },
  emptyContainer: {
    alignItems: 'center',
    paddingVertical: 24,
  },
  emptyText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#374151',
    marginTop: 12,
  },
  emptySubtext: {
    fontSize: 13,
    color: '#6b7280',
    marginTop: 4,
  },
  actionButton: {
    marginTop: 24,
    borderRadius: 12,
  },
  actionButtonContent: {
    paddingVertical: 8,
  },
});
