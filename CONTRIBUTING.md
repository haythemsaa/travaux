# Contributing to Travaux Pro 🤝

Thank you for your interest in contributing to Travaux Pro! This document provides guidelines for contributing to the project.

## Code of Conduct

By participating in this project, you agree to abide by our Code of Conduct:

- Be respectful and inclusive
- Welcome newcomers and help them learn
- Focus on what is best for the community
- Show empathy towards other community members

## How to Contribute

### Reporting Bugs 🐛

Before creating a bug report, please check existing issues to avoid duplicates.

**When reporting a bug, include:**
- Clear and descriptive title
- Steps to reproduce the bug
- Expected behavior vs actual behavior
- Screenshots if applicable
- Environment (OS, PHP version, browser, etc.)
- Error messages and logs

### Suggesting Enhancements ✨

**When suggesting an enhancement, include:**
- Clear and descriptive title
- Detailed description of the proposed feature
- Use cases and benefits
- Possible implementation approach
- Alternative solutions considered

### Pull Requests 🔀

1. **Fork the repository**
   ```bash
   git clone https://github.com/yourusername/travaux.git
   cd travaux
   ```

2. **Create a feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```

3. **Make your changes**
   - Follow coding standards (see below)
   - Add tests if applicable
   - Update documentation

4. **Commit your changes**
   ```bash
   git add .
   git commit -m "Add amazing feature"
   ```

   **Commit message format:**
   ```
   type(scope): subject

   body

   footer
   ```

   **Types:** feat, fix, docs, style, refactor, test, chore

   **Examples:**
   ```
   feat(api): add endpoint for quote comparison
   fix(auth): resolve JWT token expiration issue
   docs(readme): update installation instructions
   ```

5. **Push to your fork**
   ```bash
   git push origin feature/amazing-feature
   ```

6. **Open a Pull Request**
   - Provide a clear title and description
   - Link related issues
   - Include screenshots if UI changes
   - Request review from maintainers

## Development Setup

### Prerequisites
- PHP 8.1+
- MySQL 8.0+
- Composer
- Node.js 16+ (for mobile app)

### Local Development

1. **Clone and install**
   ```bash
   git clone https://github.com/yourusername/travaux.git
   cd travaux
   composer install
   ```

2. **Configure environment**
   ```bash
   cp .env.example .env
   # Edit .env with your local configuration
   ```

3. **Setup database**
   ```bash
   mysql -u root -p -e "CREATE DATABASE travaux_pro"
   mysql -u root -p travaux_pro < database/schema.sql
   mysql -u root -p travaux_pro < database/advanced_features.sql
   mysql -u root -p travaux_pro < database/internationalization.sql
   mysql -u root -p travaux_pro < database/custom_form_examples.sql
   mysql -u root -p travaux_pro < database/payments.sql
   ```

4. **Run development server**
   ```bash
   php -S localhost:8000 -t public
   ```

5. **Mobile app (optional)**
   ```bash
   cd mobile
   npm install
   npm start
   ```

## Coding Standards

### PHP

- Follow **PSR-12** coding standard
- Use **type declarations** for parameters and return types
- Write **PHPDoc** comments for all classes and methods
- Keep methods **short and focused** (single responsibility)
- Use **meaningful variable names**

**Example:**
```php
<?php

namespace App\Models;

/**
 * User model for managing user data
 */
class User
{
    /**
     * Find user by email
     *
     * @param string $email User email address
     * @return array|null User data or null if not found
     */
    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}
```

### JavaScript (React Native)

- Use **ES6+ features**
- Follow **Airbnb JavaScript Style Guide**
- Use **functional components** with hooks
- Write **PropTypes** or TypeScript types
- Use **meaningful component names**

**Example:**
```javascript
import React, {useState, useEffect} from 'react';
import {View, Text} from 'react-native';

const UserProfile = ({userId}) => {
    const [user, setUser] = useState(null);

    useEffect(() => {
        loadUser();
    }, [userId]);

    const loadUser = async () => {
        const response = await apiService.getUser(userId);
        if (response.success) {
            setUser(response.data.user);
        }
    };

    return (
        <View>
            {user && <Text>{user.name}</Text>}
        </View>
    );
};

export default UserProfile;
```

### SQL

- Use **uppercase** for SQL keywords
- **Indent** nested queries
- Add **meaningful comments**
- Create **indexes** for foreign keys and frequently queried columns

**Example:**
```sql
-- Find active projects with quotes count
SELECT
    p.id,
    p.title,
    p.status,
    COUNT(q.id) as quotes_count
FROM projects p
LEFT JOIN quotes q ON p.id = q.project_id
WHERE p.status = 'open'
GROUP BY p.id
ORDER BY p.created_at DESC;
```

## Testing

### PHP Tests (PHPUnit)

```bash
# Run all tests
php vendor/bin/phpunit

# Run specific test file
php vendor/bin/phpunit tests/Unit/UserTest.php

# Run with coverage
php vendor/bin/phpunit --coverage-html coverage/
```

### Mobile Tests (Jest)

```bash
cd mobile
npm test
npm test -- --coverage
```

### API Tests (Postman)

Import `/postman/travaux-pro-api-tests.json` into Postman and run the collection.

## Documentation

- Update **README.md** for major changes
- Update **API_DOCUMENTATION.md** for API changes
- Add **inline comments** for complex logic
- Update **CHANGELOG.md** for each version

## Review Process

1. **Automated Checks**
   - Code style (PSR-12)
   - Tests passing
   - No security vulnerabilities

2. **Code Review**
   - At least one approval from maintainer
   - All comments addressed
   - Passes all tests

3. **Merge**
   - Squash and merge to main branch
   - Delete feature branch

## Release Process

1. Update version in relevant files
2. Update CHANGELOG.md
3. Create release tag
4. Deploy to production

## Questions?

- **Issues:** https://github.com/yourusername/travaux/issues
- **Discussions:** https://github.com/yourusername/travaux/discussions
- **Email:** dev@travauxpro.com

## Recognition

Contributors are listed in our README.md file. Thank you for making Travaux Pro better!

---

**Happy Contributing!** 🎉
