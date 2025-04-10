<style>
:root {
    --primary-color: #493628;
    --secondary-color: #D6C0B3;
    --primary-hover: #5a442f;
    --secondary-hover: #c4ab9c;
    --text-light: #ffffff;
    --text-dark: #212529;
}

.btn-primary-custom {
    background-color: var(--primary-color);
    color: var(--text-light);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary-custom:hover {
    background-color: var(--primary-hover);
    color: var(--text-light);
}

.btn-secondary-custom {
    background-color: var(--secondary-color);
    color: var(--primary-color);
    border: none;
    transition: all 0.3s ease;
}

.btn-secondary-custom:hover {
    background-color: var(--secondary-hover);
    color: var(--primary-color);
}

.bg-primary-custom {
    background-color: var(--primary-color);
}

.bg-secondary-custom {
    background-color: var(--secondary-color);
}

.text-primary-custom {
    color: var(--primary-color);
}

.text-secondary-custom {
    color: var(--secondary-color);
}

.card-custom {
    border: 1px solid var(--secondary-color);
    border-radius: 8px;
    transition: all 0.3s ease;
}

.card-custom:hover {
    box-shadow: 0 0 15px rgba(73, 54, 40, 0.1);
}

.nav-link-custom {
    color: var(--primary-color);
    transition: all 0.3s ease;
}

.nav-link-custom:hover {
    color: var(--primary-hover);
}
</style>