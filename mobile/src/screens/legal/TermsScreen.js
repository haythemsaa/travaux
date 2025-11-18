import React from 'react';
import {ScrollView, StyleSheet} from 'react-native';
import {Text, Surface} from 'react-native-paper';

export default function TermsScreen() {
  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Surface style={styles.section}>
        <Text style={styles.title}>Conditions Générales d'Utilisation</Text>
        <Text style={styles.date}>Dernière mise à jour : Janvier 2024</Text>

        <Text style={styles.sectionTitle}>1. Acceptation des conditions</Text>
        <Text style={styles.paragraph}>
          En accédant et en utilisant Travaux Pro, vous acceptez d'être lié par
          ces conditions générales d'utilisation. Si vous n'acceptez pas ces
          conditions, veuillez ne pas utiliser notre service.
        </Text>

        <Text style={styles.sectionTitle}>2. Description du service</Text>
        <Text style={styles.paragraph}>
          Travaux Pro est une plateforme de mise en relation entre particuliers
          et artisans professionnels. Nous facilitons les échanges mais ne
          sommes pas partie prenante des contrats conclus entre utilisateurs.
        </Text>

        <Text style={styles.sectionTitle}>3. Inscription</Text>
        <Text style={styles.paragraph}>
          Pour utiliser certaines fonctionnalités, vous devez créer un compte.
          Vous vous engagez à fournir des informations exactes et à les
          maintenir à jour. Vous êtes responsable de la confidentialité de vos
          identifiants.
        </Text>

        <Text style={styles.sectionTitle}>4. Utilisation du service</Text>
        <Text style={styles.paragraph}>
          Vous vous engagez à utiliser Travaux Pro de manière légale et
          conforme à ces conditions. Il est interdit de :
        </Text>
        <Text style={styles.listItem}>
          • Publier du contenu illégal, offensant ou frauduleux
        </Text>
        <Text style={styles.listItem}>
          • Usurper l'identité d'une autre personne
        </Text>
        <Text style={styles.listItem}>
          • Perturber le fonctionnement de la plateforme
        </Text>
        <Text style={styles.listItem}>
          • Utiliser le service à des fins commerciales non autorisées
        </Text>

        <Text style={styles.sectionTitle}>5. Projets et devis</Text>
        <Text style={styles.paragraph}>
          Les particuliers peuvent publier des projets gratuitement. Les
          artisans peuvent envoyer des devis aux projets correspondant à leurs
          spécialités. Les devis constituent des offres commerciales entre
          l'artisan et le client.
        </Text>

        <Text style={styles.sectionTitle}>6. Paiements</Text>
        <Text style={styles.paragraph}>
          Les paiements sont traités via notre partenaire Stripe. Travaux Pro
          peut prélever une commission sur les transactions. Les conditions de
          paiement sont définies dans chaque devis.
        </Text>

        <Text style={styles.sectionTitle}>7. Responsabilités</Text>
        <Text style={styles.paragraph}>
          Travaux Pro agit comme intermédiaire. Nous ne sommes pas responsables
          de la qualité des travaux, des litiges entre utilisateurs, ou des
          dommages résultant de l'utilisation du service.
        </Text>

        <Text style={styles.sectionTitle}>8. Propriété intellectuelle</Text>
        <Text style={styles.paragraph}>
          Tous les contenus de la plateforme (textes, logos, graphiques) sont
          protégés par les droits de propriété intellectuelle. Toute
          reproduction non autorisée est interdite.
        </Text>

        <Text style={styles.sectionTitle}>9. Avis et évaluations</Text>
        <Text style={styles.paragraph}>
          Les utilisateurs peuvent laisser des avis. Ces avis doivent être
          honnêtes et basés sur une expérience réelle. Travaux Pro se réserve
          le droit de modérer ou supprimer les avis inappropriés.
        </Text>

        <Text style={styles.sectionTitle}>10. Suspension et résiliation</Text>
        <Text style={styles.paragraph}>
          Nous nous réservons le droit de suspendre ou résilier votre compte en
          cas de violation de ces conditions, sans préavis ni remboursement.
        </Text>

        <Text style={styles.sectionTitle}>11. Modifications</Text>
        <Text style={styles.paragraph}>
          Nous pouvons modifier ces conditions à tout moment. Les modifications
          entrent en vigueur dès leur publication. Votre utilisation continue
          constitue votre acceptation des nouvelles conditions.
        </Text>

        <Text style={styles.sectionTitle}>12. Droit applicable</Text>
        <Text style={styles.paragraph}>
          Ces conditions sont régies par le droit français. Tout litige sera
          soumis aux tribunaux compétents de Paris, France.
        </Text>

        <Text style={styles.sectionTitle}>13. Contact</Text>
        <Text style={styles.paragraph}>
          Pour toute question concernant ces conditions :
        </Text>
        <Text style={styles.contact}>
          Email : legal@travauxpro.com{'\n'}
          Adresse : 123 Rue Example, 75001 Paris, France
        </Text>
      </Surface>
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
    borderRadius: 12,
    elevation: 2,
    backgroundColor: '#fff',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#111827',
    marginBottom: 8,
  },
  date: {
    fontSize: 13,
    color: '#6b7280',
    marginBottom: 24,
    fontStyle: 'italic',
  },
  sectionTitle: {
    fontSize: 16,
    fontWeight: 'bold',
    color: '#1f2937',
    marginTop: 20,
    marginBottom: 10,
  },
  paragraph: {
    fontSize: 14,
    color: '#374151',
    lineHeight: 22,
    marginBottom: 12,
    textAlign: 'justify',
  },
  listItem: {
    fontSize: 14,
    color: '#374151',
    lineHeight: 22,
    marginBottom: 6,
    marginLeft: 10,
  },
  contact: {
    fontSize: 14,
    color: '#3b82f6',
    lineHeight: 22,
    marginTop: 8,
  },
});
