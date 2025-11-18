import React from 'react';
import {ScrollView, StyleSheet} from 'react-native';
import {Text, Surface} from 'react-native-paper';

export default function PrivacyScreen() {
  return (
    <ScrollView style={styles.container} contentContainerStyle={styles.content}>
      <Surface style={styles.section}>
        <Text style={styles.title}>Politique de Confidentialité</Text>
        <Text style={styles.date}>Dernière mise à jour : Janvier 2024</Text>

        <Text style={styles.sectionTitle}>1. Introduction</Text>
        <Text style={styles.paragraph}>
          Travaux Pro s'engage à protéger votre vie privée. Cette politique
          explique comment nous collectons, utilisons et protégeons vos données
          personnelles conformément au RGPD (Règlement Général sur la
          Protection des Données).
        </Text>

        <Text style={styles.sectionTitle}>2. Données collectées</Text>
        <Text style={styles.paragraph}>Nous collectons les données suivantes :</Text>

        <Text style={styles.subTitle}>Données d'inscription :</Text>
        <Text style={styles.listItem}>• Nom et prénom</Text>
        <Text style={styles.listItem}>• Adresse email</Text>
        <Text style={styles.listItem}>• Numéro de téléphone</Text>
        <Text style={styles.listItem}>• Mot de passe (crypté)</Text>

        <Text style={styles.subTitle}>Données de projet :</Text>
        <Text style={styles.listItem}>• Description des travaux</Text>
        <Text style={styles.listItem}>• Localisation (adresse, code postal, ville)</Text>
        <Text style={styles.listItem}>• Budget estimé</Text>
        <Text style={styles.listItem}>• Photos et documents</Text>

        <Text style={styles.subTitle}>Données techniques :</Text>
        <Text style={styles.listItem}>• Adresse IP</Text>
        <Text style={styles.listItem}>• Type d'appareil et système d'exploitation</Text>
        <Text style={styles.listItem}>• Données de navigation (cookies)</Text>
        <Text style={styles.listItem}>• Localisation GPS (avec votre consentement)</Text>

        <Text style={styles.sectionTitle}>3. Utilisation des données</Text>
        <Text style={styles.paragraph}>Nous utilisons vos données pour :</Text>
        <Text style={styles.listItem}>
          • Fournir et améliorer nos services
        </Text>
        <Text style={styles.listItem}>
          • Mettre en relation clients et artisans
        </Text>
        <Text style={styles.listItem}>
          • Traiter les paiements
        </Text>
        <Text style={styles.listItem}>
          • Envoyer des notifications importantes
        </Text>
        <Text style={styles.listItem}>
          • Analyser l'utilisation de la plateforme
        </Text>
        <Text style={styles.listItem}>
          • Prévenir la fraude et assurer la sécurité
        </Text>

        <Text style={styles.sectionTitle}>4. Partage des données</Text>
        <Text style={styles.paragraph}>
          Nous ne vendons jamais vos données. Nous les partageons uniquement :
        </Text>
        <Text style={styles.listItem}>
          • Avec les artisans pour les projets que vous publiez
        </Text>
        <Text style={styles.listItem}>
          • Avec nos prestataires techniques (hébergement, paiement)
        </Text>
        <Text style={styles.listItem}>
          • Si la loi l'exige (autorités judiciaires)
        </Text>

        <Text style={styles.sectionTitle}>5. Cookies</Text>
        <Text style={styles.paragraph}>
          Nous utilisons des cookies pour améliorer votre expérience, mémoriser
          vos préférences et analyser le trafic. Vous pouvez désactiver les
          cookies dans les paramètres de votre navigateur.
        </Text>

        <Text style={styles.sectionTitle}>6. Sécurité</Text>
        <Text style={styles.paragraph}>
          Nous mettons en œuvre des mesures de sécurité techniques et
          organisationnelles pour protéger vos données :
        </Text>
        <Text style={styles.listItem}>• Cryptage SSL/TLS</Text>
        <Text style={styles.listItem}>• Mots de passe hashés avec bcrypt</Text>
        <Text style={styles.listItem}>• Serveurs sécurisés</Text>
        <Text style={styles.listItem}>• Accès restreint aux données</Text>
        <Text style={styles.listItem}>• Audits de sécurité réguliers</Text>

        <Text style={styles.sectionTitle}>7. Vos droits (RGPD)</Text>
        <Text style={styles.paragraph}>Vous disposez des droits suivants :</Text>
        <Text style={styles.listItem}>
          • Droit d'accès : consulter vos données
        </Text>
        <Text style={styles.listItem}>
          • Droit de rectification : corriger vos données
        </Text>
        <Text style={styles.listItem}>
          • Droit à l'effacement : supprimer vos données
        </Text>
        <Text style={styles.listItem}>
          • Droit à la portabilité : récupérer vos données
        </Text>
        <Text style={styles.listItem}>
          • Droit d'opposition : refuser certains traitements
        </Text>
        <Text style={styles.listItem}>
          • Droit de limitation : restreindre le traitement
        </Text>

        <Text style={styles.paragraph}>
          Pour exercer vos droits, contactez : privacy@travauxpro.com
        </Text>

        <Text style={styles.sectionTitle}>8. Conservation des données</Text>
        <Text style={styles.paragraph}>
          Nous conservons vos données tant que votre compte est actif. Après
          suppression de votre compte, nous conservons certaines données
          pendant 3 ans maximum pour obligations légales (comptabilité,
          prévention fraude).
        </Text>

        <Text style={styles.sectionTitle}>9. Données des mineurs</Text>
        <Text style={styles.paragraph}>
          Notre service est réservé aux personnes de 18 ans et plus. Nous ne
          collectons pas sciemment de données de mineurs.
        </Text>

        <Text style={styles.sectionTitle}>10. Transferts internationaux</Text>
        <Text style={styles.paragraph}>
          Vos données sont stockées dans l'Union Européenne. Si un transfert
          hors UE est nécessaire, nous garantissons un niveau de protection
          équivalent.
        </Text>

        <Text style={styles.sectionTitle}>11. Notifications push</Text>
        <Text style={styles.paragraph}>
          Avec votre consentement, nous envoyons des notifications push. Vous
          pouvez les désactiver à tout moment dans les paramètres de
          l'application.
        </Text>

        <Text style={styles.sectionTitle}>12. Modifications</Text>
        <Text style={styles.paragraph}>
          Nous pouvons modifier cette politique. Nous vous informerons des
          changements importants par email ou notification.
        </Text>

        <Text style={styles.sectionTitle}>13. Contact</Text>
        <Text style={styles.paragraph}>
          Pour toute question sur vos données personnelles :
        </Text>
        <Text style={styles.contact}>
          Délégué à la Protection des Données{'\n'}
          Email : privacy@travauxpro.com{'\n'}
          Adresse : 123 Rue Example, 75001 Paris, France
        </Text>

        <Text style={styles.paragraph}>
          Vous pouvez également contacter la CNIL (Commission Nationale de
          l'Informatique et des Libertés) pour toute réclamation.
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
  subTitle: {
    fontSize: 14,
    fontWeight: '600',
    color: '#374151',
    marginTop: 12,
    marginBottom: 6,
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
    marginBottom: 16,
  },
});
