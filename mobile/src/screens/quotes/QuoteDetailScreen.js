import React, {useState, useEffect} from 'react';
import {View, StyleSheet, ScrollView, Alert} from 'react-native';
import {
  Text,
  Surface,
  Button,
  Chip,
  Divider,
  ActivityIndicator,
  Dialog,
  Portal,
  Paragraph,
} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';
import apiService from '../../services/api';
import {useAuth} from '../../context/AuthContext';

export default function QuoteDetailScreen({route, navigation}) {
  const {quoteId} = route.params;
  const {user} = useAuth();
  const [quote, setQuote] = useState(null);
  const [loading, setLoading] = useState(true);
  const [actionLoading, setActionLoading] = useState(false);
  const [showAcceptDialog, setShowAcceptDialog] = useState(false);
  const [showRejectDialog, setShowRejectDialog] = useState(false);

  useEffect(() => {
    loadQuoteDetails();
  }, [quoteId]);

  const loadQuoteDetails = async () => {
    try {
      const response = await apiService.get(`/quotes/${quoteId}`);
      if (response.success) {
        setQuote(response.data.quote);
      }
    } catch (error) {
      console.error('Error loading quote details:', error);
      Alert.alert('Erreur', 'Impossible de charger les détails du devis');
    } finally {
      setLoading(false);
    }
  };

  const handleAcceptQuote = async () => {
    setActionLoading(true);
    try {
      const response = await apiService.put(`/quotes/${quoteId}/accept`);
      if (response.success) {
        Alert.alert('Succès', 'Devis accepté avec succès');
        navigation.goBack();
      }
    } catch (error) {
      Alert.alert('Erreur', 'Impossible d\'accepter le devis');
    } finally {
      setActionLoading(false);
      setShowAcceptDialog(false);
    }
  };

  const handleRejectQuote = async () => {
    setActionLoading(true);
    try {
      const response = await apiService.put(`/quotes/${quoteId}/reject`);
      if (response.success) {
        Alert.alert('Succès', 'Devis refusé');
        navigation.goBack();
      }
    } catch (error) {
      Alert.alert('Erreur', 'Impossible de refuser le devis');
    } finally {
      setActionLoading(false);
      setShowRejectDialog(false);
    }
  };

  const getStatusColor = status => {
    const colors = {
      pending: '#f59e0b',
      accepted: '#10b981',
      rejected: '#ef4444',
      expired: '#6b7280',
    };
    return colors[status] || '#6b7280';
  };

  const getStatusLabel = status => {
    const labels = {
      pending: 'En attente',
      accepted: 'Accepté',
      rejected: 'Refusé',
      expired: 'Expiré',
    };
    return labels[status] || status;
  };

  const formatCurrency = (amount, currency) => {
    const symbols = {EUR: '€', USD: '$', GBP: '£', CAD: 'CA$'};
    return `${amount.toLocaleString('fr-FR')} ${symbols[currency] || currency}`;
  };

  const formatDate = dateString => {
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
      day: '2-digit',
      month: 'long',
      year: 'numeric',
    });
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#3b82f6" />
      </View>
    );
  }

  if (!quote) {
    return (
      <View style={styles.errorContainer}>
        <Text>Devis introuvable</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <ScrollView contentContainerStyle={styles.scrollContent}>
        {/* Status */}
        <Surface style={styles.statusCard}>
          <Chip
            style={[
              styles.statusChip,
              {backgroundColor: getStatusColor(quote.status) + '20'},
            ]}
            textStyle={{
              color: getStatusColor(quote.status),
              fontSize: 14,
              fontWeight: 'bold',
            }}>
            {getStatusLabel(quote.status)}
          </Chip>
        </Surface>

        {/* Project Info */}
        <Surface style={styles.section}>
          <Text style={styles.sectionTitle}>Projet</Text>
          <Text style={styles.projectTitle}>{quote.project_title}</Text>
          {user.role === 'client' && (
            <View style={styles.row}>
              <Icon name="account-hard-hat" size={18} color="#6b7280" />
              <Text style={styles.infoText}>{quote.artisan_name}</Text>
            </View>
          )}
        </Surface>

        {/* Amount */}
        <Surface style={styles.section}>
          <Text style={styles.sectionTitle}>Montant</Text>
          <Text style={styles.amount}>
            {formatCurrency(quote.amount, quote.currency_code)}
          </Text>
        </Surface>

        {/* Description */}
        {quote.description && (
          <Surface style={styles.section}>
            <Text style={styles.sectionTitle}>Description</Text>
            <Text style={styles.description}>{quote.description}</Text>
          </Surface>
        )}

        {/* Details */}
        <Surface style={styles.section}>
          <Text style={styles.sectionTitle}>Détails</Text>

          {quote.estimated_duration && (
            <View style={styles.detailRow}>
              <Icon name="clock-outline" size={20} color="#6b7280" />
              <View style={styles.detailContent}>
                <Text style={styles.detailLabel}>Durée estimée</Text>
                <Text style={styles.detailValue}>
                  {quote.estimated_duration}
                </Text>
              </View>
            </View>
          )}

          {quote.start_date && (
            <View style={styles.detailRow}>
              <Icon name="calendar-start" size={20} color="#6b7280" />
              <View style={styles.detailContent}>
                <Text style={styles.detailLabel}>Date de début</Text>
                <Text style={styles.detailValue}>
                  {formatDate(quote.start_date)}
                </Text>
              </View>
            </View>
          )}

          {quote.payment_terms && (
            <View style={styles.detailRow}>
              <Icon name="cash-multiple" size={20} color="#6b7280" />
              <View style={styles.detailContent}>
                <Text style={styles.detailLabel}>Conditions de paiement</Text>
                <Text style={styles.detailValue}>{quote.payment_terms}</Text>
              </View>
            </View>
          )}

          {quote.valid_until && (
            <View style={styles.detailRow}>
              <Icon name="timer-sand" size={20} color="#6b7280" />
              <View style={styles.detailContent}>
                <Text style={styles.detailLabel}>Valide jusqu'au</Text>
                <Text style={styles.detailValue}>
                  {formatDate(quote.valid_until)}
                </Text>
              </View>
            </View>
          )}

          <View style={styles.detailRow}>
            <Icon name="calendar-clock" size={20} color="#6b7280" />
            <View style={styles.detailContent}>
              <Text style={styles.detailLabel}>Date d'envoi</Text>
              <Text style={styles.detailValue}>
                {formatDate(quote.created_at)}
              </Text>
            </View>
          </View>
        </Surface>
      </ScrollView>

      {/* Actions for client */}
      {user.role === 'client' && quote.status === 'pending' && (
        <View style={styles.actions}>
          <Button
            mode="outlined"
            onPress={() => setShowRejectDialog(true)}
            style={styles.rejectButton}
            textColor="#ef4444">
            Refuser
          </Button>
          <Button
            mode="contained"
            onPress={() => setShowAcceptDialog(true)}
            style={styles.acceptButton}
            buttonColor="#10b981">
            Accepter
          </Button>
        </View>
      )}

      {/* Accept Dialog */}
      <Portal>
        <Dialog
          visible={showAcceptDialog}
          onDismiss={() => setShowAcceptDialog(false)}>
          <Dialog.Title>Accepter le devis</Dialog.Title>
          <Dialog.Content>
            <Paragraph>
              Êtes-vous sûr de vouloir accepter ce devis de{' '}
              {formatCurrency(quote.amount, quote.currency_code)} ?
            </Paragraph>
          </Dialog.Content>
          <Dialog.Actions>
            <Button onPress={() => setShowAcceptDialog(false)}>Annuler</Button>
            <Button
              onPress={handleAcceptQuote}
              loading={actionLoading}
              textColor="#10b981">
              Accepter
            </Button>
          </Dialog.Actions>
        </Dialog>

        <Dialog
          visible={showRejectDialog}
          onDismiss={() => setShowRejectDialog(false)}>
          <Dialog.Title>Refuser le devis</Dialog.Title>
          <Dialog.Content>
            <Paragraph>Êtes-vous sûr de vouloir refuser ce devis ?</Paragraph>
          </Dialog.Content>
          <Dialog.Actions>
            <Button onPress={() => setShowRejectDialog(false)}>Annuler</Button>
            <Button
              onPress={handleRejectQuote}
              loading={actionLoading}
              textColor="#ef4444">
              Refuser
            </Button>
          </Dialog.Actions>
        </Dialog>
      </Portal>
    </View>
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
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  scrollContent: {
    padding: 15,
  },
  statusCard: {
    padding: 15,
    marginBottom: 15,
    borderRadius: 12,
    alignItems: 'center',
    elevation: 2,
  },
  statusChip: {
    paddingHorizontal: 12,
  },
  section: {
    padding: 20,
    marginBottom: 15,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  sectionTitle: {
    fontSize: 12,
    fontWeight: '600',
    color: '#6b7280',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginBottom: 12,
  },
  projectTitle: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 8,
  },
  row: {
    flexDirection: 'row',
    alignItems: 'center',
    marginTop: 8,
  },
  infoText: {
    fontSize: 14,
    color: '#6b7280',
    marginLeft: 8,
  },
  amount: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#10b981',
  },
  description: {
    fontSize: 15,
    color: '#374151',
    lineHeight: 24,
  },
  detailRow: {
    flexDirection: 'row',
    alignItems: 'flex-start',
    marginBottom: 16,
  },
  detailContent: {
    flex: 1,
    marginLeft: 12,
  },
  detailLabel: {
    fontSize: 13,
    color: '#6b7280',
    marginBottom: 2,
  },
  detailValue: {
    fontSize: 15,
    color: '#111827',
    fontWeight: '500',
  },
  actions: {
    flexDirection: 'row',
    padding: 15,
    backgroundColor: '#fff',
    borderTopWidth: 1,
    borderTopColor: '#e5e7eb',
    gap: 10,
  },
  rejectButton: {
    flex: 1,
    borderColor: '#ef4444',
  },
  acceptButton: {
    flex: 1,
  },
});
