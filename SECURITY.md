# Security Policy

## Supported Versions

We release security updates for the following versions:

| Version | Supported          |
| ------- | ------------------ |
| 1.0.x   | :white_check_mark: |
| < 1.0   | :x:                |

## Reporting a Vulnerability

We take security seriously at Travaux Pro. If you discover a security vulnerability, please follow these steps:

### 1. Do Not Publicly Disclose

Please **do not** create a public GitHub issue for security vulnerabilities. This could put all users at risk.

### 2. Contact Us Securely

Send details to: **security@travauxpro.com**

Optionally, use our PGP key for encryption: [PGP Key](https://travauxpro.com/.well-known/pgp-key.txt)

### 3. Include These Details

- **Type of vulnerability** (SQL injection, XSS, CSRF, etc.)
- **Location** (file path, URL, specific function)
- **Steps to reproduce** the vulnerability
- **Potential impact** of the exploit
- **Proof of concept** code (if applicable)
- **Suggested fix** (if you have one)

### 4. Response Timeline

| Timeline | Action |
|----------|--------|
| **Within 24 hours** | Initial acknowledgment of your report |
| **Within 72 hours** | Preliminary assessment and severity rating |
| **Within 7 days** | Detailed response with our action plan |
| **Depends on severity** | Fix deployment (see below) |

### 5. Fix Deployment Schedule

| Severity | Fix Timeline |
|----------|--------------|
| **Critical** | 24-48 hours |
| **High** | 1 week |
| **Medium** | 2 weeks |
| **Low** | 1 month |

## Security Measures

### Current Security Implementations

#### Authentication & Authorization
- ✅ **JWT Tokens** - Stateless authentication
- ✅ **Password Hashing** - Bcrypt with salt
- ✅ **Session Security** - Secure, HttpOnly cookies
- ✅ **Role-Based Access** - Client, Artisan, Admin roles
- ✅ **Token Expiration** - 24-hour JWT lifespan
- ✅ **Password Requirements** - Minimum 6 characters (should be increased to 8+)

#### Data Protection
- ✅ **SQL Injection Prevention** - Prepared statements everywhere
- ✅ **XSS Prevention** - Input sanitization and output encoding
- ✅ **CSRF Protection** - Token-based verification
- ✅ **Data Encryption** - HTTPS/SSL for data in transit
- ✅ **Input Validation** - Server-side validation for all inputs
- ✅ **File Upload Security** - Type and size restrictions

#### Infrastructure Security
- ✅ **Security Headers** - XSS, Clickjacking, MIME-sniffing protection
- ✅ **HTTPS Enforcement** - Redirect HTTP to HTTPS
- ✅ **Rate Limiting** - Prevent brute force attacks
- ✅ **CORS Configuration** - Controlled cross-origin requests
- ✅ **Server Hardening** - Hidden server signatures
- ✅ **Directory Browsing Disabled** - Prevent file listing

#### API Security
- ✅ **Authentication Required** - JWT for all protected endpoints
- ✅ **Input Validation** - All API inputs validated
- ✅ **Rate Limiting** - Per-user API call limits
- ✅ **CORS** - Controlled API access
- ✅ **Error Handling** - No sensitive data in errors

#### Database Security
- ✅ **Prepared Statements** - No raw SQL concatenation
- ✅ **Least Privilege** - Limited database user permissions
- ✅ **Connection Security** - Secured MySQL connections
- ✅ **Backup Encryption** - Encrypted database backups
- ✅ **Regular Backups** - Automated daily backups

#### Payment Security
- ✅ **Stripe Integration** - PCI DSS compliant
- ✅ **No Card Storage** - Stripe handles all card data
- ✅ **Webhook Verification** - Stripe signature validation
- ✅ **3D Secure** - Strong customer authentication
- ✅ **Secure Tokens** - Payment intent tokens

### Known Limitations

We acknowledge the following security considerations:

1. **Password Complexity** - Currently minimum 6 characters (recommend 8+ with complexity requirements)
2. **2FA Not Implemented** - Two-factor authentication not yet available
3. **Account Lockout** - No automatic lockout after failed login attempts
4. **Session Timeout** - 24-hour sessions (consider shorter for sensitive operations)
5. **Content Security Policy** - Could be more restrictive
6. **API Versioning** - Not yet implemented for backward compatibility

### Security Roadmap

Planned security improvements:

- [ ] Two-Factor Authentication (2FA)
- [ ] Account lockout after failed attempts
- [ ] Stronger password requirements
- [ ] Security audit logging
- [ ] Automated security scanning
- [ ] Penetration testing
- [ ] Bug bounty program
- [ ] SOC 2 compliance

## Best Practices for Deployment

### Production Checklist

- [ ] **Change default secrets** in .env (JWT_SECRET, database passwords)
- [ ] **Enable HTTPS** - Obtain and configure SSL certificate
- [ ] **Configure firewalls** - Restrict database access
- [ ] **Regular updates** - Keep PHP, MySQL, packages updated
- [ ] **Backup strategy** - Automated backups with offsite storage
- [ ] **Monitoring** - Set up error and security monitoring
- [ ] **Rate limiting** - Enable for login and API endpoints
- [ ] **Security headers** - Uncomment HSTS and CSP in production
- [ ] **Disable debug mode** - Set APP_DEBUG=false
- [ ] **Remove dev tools** - No PHPMyAdmin or development endpoints

### Environment Variables

**Never commit these to version control:**

```bash
# Critical secrets
JWT_SECRET=your_secret_here
DB_PASSWORD=your_password_here
STRIPE_SECRET_KEY=sk_live_xxx
FIREBASE_SERVER_KEY=your_key_here

# Production settings
APP_ENV=production
APP_DEBUG=false
HTTPS_ONLY=true
```

### File Permissions

```bash
# Recommended permissions
chmod 755 /var/www/travaux
chmod 644 /var/www/travaux/**/*.php
chmod 600 /var/www/travaux/.env
chmod 775 /var/www/travaux/uploads
chmod 775 /var/www/travaux/storage
```

## Responsible Disclosure

We follow coordinated disclosure:

1. **Private report** to security@travauxpro.com
2. **Investigation** and fix development (7-90 days depending on severity)
3. **Fix deployment** to production
4. **Public disclosure** after fix is deployed (with your permission)
5. **Credit** to security researcher (if desired)

## Bug Bounty

We currently **do not** have a formal bug bounty program, but we:

- **Appreciate** all security reports
- **Acknowledge** researchers publicly (with permission)
- **Respond promptly** to all reports
- May offer **rewards** for critical vulnerabilities on a case-by-case basis

## Hall of Fame

Security researchers who have responsibly disclosed vulnerabilities:

*Coming soon - be the first!*

## Contact

- **Security Email:** security@travauxpro.com
- **General Support:** support@travauxpro.com
- **PGP Key:** https://travauxpro.com/.well-known/pgp-key.txt
- **Security.txt:** https://travauxpro.com/.well-known/security.txt

## Additional Resources

- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)
- [Stripe Security](https://stripe.com/docs/security/stripe)
- [Firebase Security](https://firebase.google.com/docs/rules)

---

**Thank you for helping keep Travaux Pro secure!** 🔒
