import React, {useState} from 'react';
import {View, StyleSheet, ScrollView} from 'react-native';
import {Text, Surface, List, Searchbar} from 'react-native-paper';
import Icon from 'react-native-vector-icons/MaterialCommunityIcons';

export default function HelpScreen() {
  const [searchQuery, setSearchQuery] = useState('');
  const [expandedId, setExpandedId] = useState(null);

  const faqData = [
    {
      id: 1,
      category: 'Général',
      question: 'Comment fonctionne Travaux Pro ?',
      answer:
        'Travaux Pro met en relation des particuliers avec des artisans qualifiés. Vous postez votre projet, les artisans vous envoient des devis, et vous choisissez celui qui vous convient le mieux.',
    },
    {
      id: 2,
      category: 'Projets',
      question: 'Comment créer un projet ?',
      answer:
        'Appuyez sur le bouton + dans l\'onglet Projets, remplissez les informations (titre, description, budget, localisation) et validez. Les artisans de votre région recevront une notification.',
    },
    {
      id: 3,
      category: 'Projets',
      question: 'Combien de temps pour recevoir des devis ?',
      answer:
        'En général, vous commencez à recevoir des devis dans les 24-48h après publication de votre projet. Cela dépend de votre localisation et du type de travaux.',
    },
    {
      id: 4,
      category: 'Devis',
      question: 'Comment accepter un devis ?',
      answer:
        'Ouvrez le devis depuis l\'onglet Projets ou Notifications, consultez les détails, et appuyez sur "Accepter". Vous pourrez ensuite contacter directement l\'artisan.',
    },
    {
      id: 5,
      category: 'Devis',
      question: 'Puis-je négocier un devis ?',
      answer:
        'Oui ! Utilisez la messagerie intégrée pour discuter avec l\'artisan. Vous pouvez lui demander des ajustements ou des précisions avant d\'accepter.',
    },
    {
      id: 6,
      category: 'Paiement',
      question: 'Comment se passe le paiement ?',
      answer:
        'Le paiement s\'effectue selon les conditions définies dans le devis (acompte, échelonnement, etc.). Travaux Pro sécurise les transactions via Stripe.',
    },
    {
      id: 7,
      category: 'Artisans',
      question: 'Les artisans sont-ils vérifiés ?',
      answer:
        'Oui, tous les artisans doivent fournir leur SIRET et leurs assurances professionnelles. Nous vérifions également les avis clients.',
    },
    {
      id: 8,
      category: 'Artisans',
      question: 'Comment devenir artisan sur la plateforme ?',
      answer:
        'Créez un compte artisan, complétez votre profil professionnel avec vos certifications, spécialités, et exemples de réalisations. L\'inscription est gratuite.',
    },
    {
      id: 9,
      category: 'Compte',
      question: 'Comment modifier mes informations ?',
      answer:
        'Allez dans Profil > Modifier le profil. Vous pouvez y changer votre nom, email, téléphone et mot de passe.',
    },
    {
      id: 10,
      category: 'Notifications',
      question: 'Comment gérer mes notifications ?',
      answer:
        'Allez dans Profil > Paramètres > Notifications. Vous pouvez activer/désactiver chaque type de notification (nouveaux devis, messages, etc.).',
    },
    {
      id: 11,
      category: 'Sécurité',
      question: 'Mes données sont-elles sécurisées ?',
      answer:
        'Oui, nous utilisons le cryptage SSL et respectons le RGPD. Vos données ne sont jamais partagées avec des tiers sans votre consentement.',
    },
    {
      id: 12,
      category: 'Support',
      question: 'Comment contacter le support ?',
      answer:
        'Envoyez un email à support@travauxpro.com ou utilisez le formulaire de contact. Notre équipe répond sous 24h maximum.',
    },
  ];

  const filteredFaq = searchQuery
    ? faqData.filter(
        item =>
          item.question.toLowerCase().includes(searchQuery.toLowerCase()) ||
          item.answer.toLowerCase().includes(searchQuery.toLowerCase()) ||
          item.category.toLowerCase().includes(searchQuery.toLowerCase()),
      )
    : faqData;

  const categories = [...new Set(faqData.map(item => item.category))];

  return (
    <View style={styles.container}>
      <Searchbar
        placeholder="Rechercher dans l'aide..."
        onChangeText={setSearchQuery}
        value={searchQuery}
        style={styles.searchBar}
      />

      <ScrollView contentContainerStyle={styles.content}>
        <Surface style={styles.contactCard}>
          <Icon name="headset" size={32} color="#3b82f6" />
          <Text style={styles.contactTitle}>Besoin d'aide ?</Text>
          <Text style={styles.contactText}>
            Notre équipe est là pour vous aider
          </Text>
          <Text style={styles.contactEmail}>support@travauxpro.com</Text>
        </Surface>

        {!searchQuery &&
          categories.map(category => (
            <View key={category}>
              <Text style={styles.categoryTitle}>{category}</Text>
              {faqData
                .filter(item => item.category === category)
                .map(item => (
                  <List.Accordion
                    key={item.id}
                    title={item.question}
                    titleNumberOfLines={2}
                    expanded={expandedId === item.id}
                    onPress={() =>
                      setExpandedId(expandedId === item.id ? null : item.id)
                    }
                    style={styles.accordion}
                    left={props => <List.Icon {...props} icon="help-circle" />}>
                    <Surface style={styles.answerContainer}>
                      <Text style={styles.answer}>{item.answer}</Text>
                    </Surface>
                  </List.Accordion>
                ))}
            </View>
          ))}

        {searchQuery &&
          (filteredFaq.length > 0 ? (
            filteredFaq.map(item => (
              <List.Accordion
                key={item.id}
                title={item.question}
                titleNumberOfLines={2}
                description={item.category}
                expanded={expandedId === item.id}
                onPress={() =>
                  setExpandedId(expandedId === item.id ? null : item.id)
                }
                style={styles.accordion}
                left={props => <List.Icon {...props} icon="help-circle" />}>
                <Surface style={styles.answerContainer}>
                  <Text style={styles.answer}>{item.answer}</Text>
                </Surface>
              </List.Accordion>
            ))
          ) : (
            <View style={styles.emptyContainer}>
              <Icon name="magnify-close" size={64} color="#d1d5db" />
              <Text style={styles.emptyText}>Aucun résultat</Text>
              <Text style={styles.emptySubtext}>
                Essayez avec d'autres mots-clés
              </Text>
            </View>
          ))}

        <Surface style={styles.moreHelpCard}>
          <Text style={styles.moreHelpTitle}>Vous ne trouvez pas votre réponse ?</Text>
          <Text style={styles.moreHelpText}>
            Contactez notre équipe support qui se fera un plaisir de vous aider
          </Text>
        </Surface>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#f3f4f6',
  },
  searchBar: {
    margin: 15,
    elevation: 2,
  },
  content: {
    padding: 15,
    paddingTop: 0,
  },
  contactCard: {
    padding: 25,
    marginBottom: 20,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
    alignItems: 'center',
  },
  contactTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#111827',
    marginTop: 12,
    marginBottom: 8,
  },
  contactText: {
    fontSize: 14,
    color: '#6b7280',
    marginBottom: 8,
  },
  contactEmail: {
    fontSize: 15,
    color: '#3b82f6',
    fontWeight: '600',
  },
  categoryTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#6b7280',
    textTransform: 'uppercase',
    letterSpacing: 0.5,
    marginTop: 10,
    marginBottom: 10,
    marginLeft: 5,
  },
  accordion: {
    backgroundColor: '#fff',
    marginBottom: 8,
    borderRadius: 8,
    elevation: 1,
  },
  answerContainer: {
    padding: 15,
    backgroundColor: '#f9fafb',
    marginHorizontal: 10,
    marginBottom: 10,
    borderRadius: 8,
  },
  answer: {
    fontSize: 14,
    color: '#374151',
    lineHeight: 22,
  },
  emptyContainer: {
    alignItems: 'center',
    justifyContent: 'center',
    paddingVertical: 60,
  },
  emptyText: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#6b7280',
    marginTop: 16,
    marginBottom: 8,
  },
  emptySubtext: {
    fontSize: 14,
    color: '#9ca3af',
  },
  moreHelpCard: {
    padding: 20,
    marginTop: 20,
    marginBottom: 30,
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#eff6ff',
  },
  moreHelpTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1e40af',
    marginBottom: 8,
  },
  moreHelpText: {
    fontSize: 14,
    color: '#3b82f6',
    lineHeight: 20,
  },
});
