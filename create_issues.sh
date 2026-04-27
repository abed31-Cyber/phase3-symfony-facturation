#!/bin/bash
OWNER="abed31-Cyber"
REPO="phase3-symfony-facturation"

create_issue() {
    local title="$1"
    local body="$2"
    local labels="$3"
    echo "🚀 Création : $title"
    gh issue create --repo "$OWNER/$REPO" --title "$title" --body "$body" --label "$labels" --milestone "MVP"
    sleep 1
}

# --- EPIC 1 ---
create_issue "[EPIC 1] Espace Utilisateur (MVP)" "Authentification et dashboard." "epic,backend,security"
create_issue "[EPIC 1.1.1] Entité User & Migrations" "- [ ] make:user\n- [ ] Ajout champs métier\n- [ ] migration" "backend,database"
create_issue "[EPIC 1.1.2] Formulaire d'inscription" "RegistrationFormType et Twig." "backend,frontend"

# --- EPIC 2 ---
create_issue "[EPIC 2] Facturation (MVP)" "Gestion métier." "epic,backend,database"
create_issue "[EPIC 2.1.1] Entité Product & CRUD" "- [ ] Entity Product\n- [ ] Controller CRUD" "backend,database"
create_issue "[EPIC 2.2.1] Entité Client & CRUD" "- [ ] Entity Client\n- [ ] Formulaire" "backend,database"
create_issue "[EPIC 2.3.1] Logique de Facturation" "Calculs totaux et validation." "backend"

# --- EPIC 3 & 4 ---
create_issue "[EPIC 3] Emailing" "Envoi PDF." "epic,email"
create_issue "[EPIC 4] Statistiques" "Charts CA." "epic,chart"

