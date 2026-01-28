# Hayat Camp System Plan

## 1. Project Setup
- [ ] Initialize Laravel project (In Progress)
- [ ] Install Livewire
- [ ] Setup database (SQLite for convenience)
- [ ] Install SweetAlert2 (via CDN or npm)

## 2. Database Schema
### `families`
- `id`
- `husband_name`
- `wife_name`
- `husband_id_number`
- `wife_id_number`
- `husband_dob` (Date)
- `wife_dob` (Date)
- `husband_phone`
- `wife_phone`
- `original_residence`
- `current_residence`
- `created_at`, `updated_at`

### `family_members` (Children)
- `id`
- `family_id` (FK)
- `name`
- `id_number`
- `dob` (Date)
- `gender` (enum: 'male', 'female')
- `created_at`, `updated_at`

### `health_conditions`
- `id`
- `family_id` (FK)
- `person_name`
- `condition_details`
- `created_at`, `updated_at`

## 3. Models & Relationships
- `Family` has many `FamilyMember`
- `Family` has many `HealthCondition`

## 4. UI Components (Livewire)
- `CreateFamily`: Wizard or simple form for all data.
- `FamilyTable`: List all families with filters.
- `SonTable`: List male children with filters.
- `DaughterTable`: List female children with filters.
- `FamilyDetails`: Single page view for a family.

## 5. Filters Logic
- Search: Global search across names, IDs, phones.
- Age: Popup with direction (older/younger) and target age.
- Gender: Applied automatically in Sons/Daughters tables, selectable in main?
- Diseases: Toggle for families with health conditions.

## 6. Aesthetics
- RTL Layout (Arabic).
- Dark/Light mode support (Premium look).
- Glassmorphism for cards.
- SweetAlert2 for notifications.
