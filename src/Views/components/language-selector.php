<!-- Language Selector Component -->
<div class="language-selector">
    <?php
    use App\Utils\i18n;

    $currentLang = $_SESSION['language'] ?? 'fr';
    $currentCountry = $_SESSION['country_code'] ?? 'FR';
    $languages = i18n::getAvailableLanguages();

    $countryModel = new \App\Models\Country();
    $countries = $countryModel->getAllActive();
    ?>

    <div class="dropdown">
        <button class="btn btn-sm btn-outline dropdown-toggle" id="languageDropdown">
            <?= $languages[$currentLang]['flag'] ?? '🌐' ?>
            <?= $languages[$currentLang]['name'] ?? 'Language' ?>
        </button>

        <div class="dropdown-menu" aria-labelledby="languageDropdown">
            <?php foreach ($languages as $code => $lang): ?>
                <form action="/localization/change-language" method="POST" style="display: inline;">
                    <input type="hidden" name="language" value="<?= $code ?>">
                    <input type="hidden" name="redirect" value="<?= $_SERVER['REQUEST_URI'] ?>">
                    <button type="submit" class="dropdown-item <?= $currentLang === $code ? 'active' : '' ?>">
                        <?= $lang['flag'] ?> <?= $lang['name'] ?>
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="dropdown ms-2">
        <button class="btn btn-sm btn-outline dropdown-toggle" id="countryDropdown">
            <i class="fas fa-globe"></i>
            <?= $currentCountry ?>
        </button>

        <div class="dropdown-menu dropdown-menu-scrollable" aria-labelledby="countryDropdown">
            <?php foreach ($countries as $country): ?>
                <form action="/localization/change-country" method="POST" style="display: inline;">
                    <input type="hidden" name="country_code" value="<?= $country['code'] ?>">
                    <input type="hidden" name="redirect" value="<?= $_SERVER['REQUEST_URI'] ?>">
                    <button type="submit" class="dropdown-item <?= $currentCountry === $country['code'] ? 'active' : '' ?>">
                        <?= htmlspecialchars($country['name']) ?> (<?= $country['code'] ?>)
                    </button>
                </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.language-selector {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-toggle {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.dropdown-menu {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: var(--border-radius);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    min-width: 200px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
    margin-top: 0.25rem;
}

.dropdown:hover .dropdown-menu,
.dropdown:focus-within .dropdown-menu {
    display: block;
}

.dropdown-item {
    display: block;
    width: 100%;
    padding: 0.5rem 1rem;
    border: none;
    background: none;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.2s;
}

.dropdown-item:hover {
    background-color: #f3f4f6;
}

.dropdown-item.active {
    background-color: #e5e7eb;
    font-weight: bold;
}

.dropdown-menu-scrollable {
    max-height: 300px;
}
</style>

<script>
// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.dropdown');

    dropdowns.forEach(dropdown => {
        if (!dropdown.contains(event.target)) {
            const menu = dropdown.querySelector('.dropdown-menu');
            if (menu) {
                menu.style.display = 'none';
            }
        }
    });
});
</script>
