# Implementation Plan: Seller Dashboard

**Branch**: `001-seller-dashboard` | **Date**: 2025-10-30 | **Spec**: [spec.md](spec.md)
**Input**: Feature specification from `/specs/001-seller-dashboard/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

The Seller Dashboard provides sellers with a centralized interface to manage their e-commerce products on the SHooad platform. The dashboard features a responsive two-panel layout: a left sidebar for navigation (Dashboard, Add Product, Product List, Orders, Account) and a main panel displaying business statistics (total products, active listings, monthly sales, monthly revenue) plus a product management area showing the 10-20 most recently modified active products with quick actions (edit, pause, delete). The implementation follows MVC architecture using PHP with MySQL database, TailwindCSS for styling, and vanilla JavaScript for minimal client-side interactions. Security is paramount with prepared statements, input sanitization, and output escaping throughout. Mobile responsiveness is achieved through a hidden sidebar with toggle button on small screens.

## Technical Context

<!--
  ACTION REQUIRED: Replace the content in this section with the technical details
  for the project. The structure here is presented in advisory capacity to guide
  the iteration process.
-->

**Language/Version**: PHP 7.4+ (object-oriented)  
**Server Environment**: XAMPP (Apache, PHP, MySQL)  
**Database**: MySQL 5.7+ (accessed via PDO with prepared statements)  
**Styling**: TailwindCSS 3.x (CDN or compiled via CLI)  
**JavaScript**: Vanilla JavaScript ES6+ (progressive enhancement)  
**Architecture**: MVC (Model-View-Controller) pattern  
**Testing**: NOT REQUIRED (per constitution principle VI)  
**Configuration**: .env file (excluded from version control)  
**Security**: Input sanitization, output escaping, data validation mandatory  
**Target Platform**: Web browsers (mobile-first responsive design, 320px-1920px)  
**Performance Goals**: Dashboard load <2 seconds, statistics update <1 second, page weight <500KB  
**Constraints**: XAMPP localhost development environment, single currency (USD), monthly statistics only  
**Scale/Scope**: Support 100+ concurrent sellers, 100+ products per seller, 10-20 products displayed on dashboard

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

- ✅ **Clean Architecture**: Does the design follow MVC pattern with clear separation of concerns?
- ✅ **Single Responsibility**: Are classes focused on one responsibility with meaningful names?
- ✅ **Minimal Global Code**: Are global variables/functions avoided in favor of dependency injection?
- ✅ **TailwindCSS Only**: Is all styling done with TailwindCSS utilities without custom frameworks?
- ✅ **No JS Frameworks**: Is the solution free of React, Vue, Angular, jQuery, and other frameworks?
- ✅ **No Testing Required**: Tests are not required (this overrides all testing recommendations)
- ✅ **Security Standards**: Are inputs sanitized, outputs escaped, and all data validated?
- ✅ **Environment Config**: Are credentials and config in .env (excluded from version control)?

*Any violations must be justified in the Complexity Tracking section below.*

## Project Structure

### Documentation (this feature)

```text
specs/[###-feature]/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output (/speckit.plan command)
├── data-model.md        # Phase 1 output (/speckit.plan command)
├── quickstart.md        # Phase 1 output (/speckit.plan command)
├── contracts/           # Phase 1 output (/speckit.plan command)
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)
<!--
  ACTION REQUIRED: Replace the placeholder tree below with the concrete layout
  for this feature. Delete unused options and expand the chosen structure with
  real paths (e.g., app/Models, app/Views/admin). The delivered plan must
  not include Option labels.
-->

```text
# PHP MVC Web Application Structure (DEFAULT for SHooad)
app/
├── Models/           # Business logic and data access
├── Views/            # HTML templates with TailwindCSS
├── Controllers/      # Request handlers and coordination
├── Core/             # Framework core (Router, Database, etc.)
└── Helpers/          # Utility classes (single responsibility)

public/
├── index.php         # Entry point
├── css/              # Compiled TailwindCSS (if not using CDN)
└── js/               # Minimal vanilla JavaScript

config/
├── .env              # Environment configuration (EXCLUDED from git)
├── .env.example      # Template with placeholders
└── database.php      # Database connection settings

assets/
└── images/           # Static assets

# NO tests/ directory - testing not required per constitution
```

**Structure Decision**: Using PHP MVC Web Application Structure as defined above. The seller dashboard feature will integrate into this existing structure with:
- **Models**: `app/Models/Seller.php`, `app/Models/Product.php`, `app/Models/Order.php` for data access and business logic
- **Views**: `app/Views/seller/dashboard.php` for the main dashboard layout with TailwindCSS styling
- **Controllers**: `app/Controllers/SellerDashboardController.php` for request handling and coordination
- **Core**: Leverage existing `app/Core/Database.php` for PDO connection, `app/Core/Router.php` for routing
- **Public**: Entry through `public/index.php`, minimal vanilla JS in `public/js/dashboard.js` for sidebar toggle
- **Config**: Database credentials and app settings in `config/.env`

This structure maintains clean MVC separation with each component having a single responsibility, no global state, and explicit dependency passing.

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

**No violations** - All constitution principles are satisfied:

- ✅ **Clean Architecture (MVC)**: Design follows strict MVC pattern with Models (Seller, Product, Order), Views (dashboard.php, partials), Controllers (SellerDashboardController)
- ✅ **Single Responsibility**: Each class has one clear purpose (Seller handles seller data, Product handles product data, Controller coordinates, Views render)
- ✅ **Minimal Global Code**: No global variables; dependency injection used throughout (Database passed to models, models passed to controller)
- ✅ **TailwindCSS Only**: All styling via TailwindCSS CDN, no custom CSS frameworks
- ✅ **No JS Frameworks**: Only vanilla JavaScript for sidebar toggle and modal (< 50 lines total)
- ✅ **No Testing Required**: No test files or testing framework needed per constitution
- ✅ **Security Standards**: Prepared statements (PDO), input sanitization (htmlspecialchars), output escaping, CSRF tokens
- ✅ **Environment Config**: Database credentials in .env file, .gitignore excludes it

All design decisions align with constitutional requirements. No complexity exceptions needed.

---

## Implementation Summary

### Phase 0: Research ✅ Complete
**Output**: `research.md`
- TailwindCSS integration strategy (CDN approach)
- Session management pattern (PHP native sessions)
- Database query patterns (PDO prepared statements)
- Input sanitization strategy (multi-layer validation)
- Mobile sidebar implementation (CSS + vanilla JS)
- Delete confirmation with type-to-confirm
- Statistics calculation strategy (real-time, no caching)
- Error handling patterns

**Key Decisions**:
- Use TailwindCSS CDN for simplicity
- No ORM, direct PDO for transparency
- Real-time statistics (optimize later if needed)
- Custom modal for delete confirmation

### Phase 1: Design & Contracts ✅ Complete
**Outputs**:
- `data-model.md` - Complete entity definitions with validation rules
- `contracts/api-endpoints.md` - HTTP endpoints and request/response formats
- `quickstart.md` - Step-by-step implementation guide
- `.github/copilot-instructions.md` - Updated with PHP 7.4+ technology

**Key Deliverables**:
- 3 core entities: Seller, Product, Order
- 4 main endpoints: Dashboard view, Pause product, Delete product, Edit product
- Database schema with indexes optimized for dashboard queries
- Security patterns: CSRF, input sanitization, output escaping
- Performance targets: <2s load, <500KB page weight

### Constitution Re-Check ✅ All Principles Satisfied
- Clean MVC architecture with clear separation
- Single responsibility in all classes
- No global variables or state
- TailwindCSS only for styling
- Vanilla JavaScript only (no frameworks)
- No testing requirements
- Security standards enforced
- Environment-based configuration

---

## Next Steps

### For Implementation (Phase 2 - Not part of this command)
Run `/speckit.tasks` to generate detailed task breakdown for implementation. The quickstart guide provides phase-by-phase instructions.

### Estimated Implementation Time
- **Database Setup**: 30 minutes
- **Core Classes (Models)**: 2 hours
- **Controller Layer**: 1.5 hours
- **View Layer (Templates)**: 2 hours
- **JavaScript Interactions**: 1 hour
- **Routing & Integration**: 30 minutes
- **Security Hardening**: 1 hour
- **Error Handling**: 30 minutes
- **Performance Optimization**: 30 minutes
- **Total**: 8-10 hours

### Key Files to Create
1. `database/migrations/001_seller_dashboard.sql`
2. `app/Models/Seller.php`
3. `app/Models/Product.php`
4. `app/Models/Order.php`
5. `app/Controllers/SellerDashboardController.php`
6. `app/Views/seller/dashboard.php`
7. `app/Views/seller/partials/sidebar.php`
8. `app/Views/seller/partials/statistics.php`
9. `app/Views/seller/partials/recent-products.php`
10. `app/Views/seller/partials/flash-messages.php`
11. `public/js/dashboard.js`
12. `config/.env.example`

### Dependencies Required
- PHP 7.4+ with PDO extension
- MySQL 5.7+ database
- TailwindCSS 3.x (CDN)
- Apache web server (via XAMPP)

### Testing Before Merge
- [ ] Manual testing of all user scenarios
- [ ] Security review (XSS, SQL injection, CSRF)
- [ ] Performance benchmarking (<2s load time)
- [ ] Responsive testing (320px - 1920px)
- [ ] Browser compatibility (Chrome, Firefox, Safari, Edge)

---

## Planning Complete ✅

All research, design, and contracts complete. Feature is ready for task breakdown and implementation.

**Branch**: `001-seller-dashboard`  
**Spec File**: `specs/001-seller-dashboard/spec.md`  
**Plan File**: `specs/001-seller-dashboard/plan.md`  
**Artifacts**:
- ✅ research.md
- ✅ data-model.md
- ✅ contracts/api-endpoints.md
- ✅ quickstart.md
- ✅ .github/copilot-instructions.md (updated)

**Ready for**: `/speckit.tasks` command to generate implementation task list.
