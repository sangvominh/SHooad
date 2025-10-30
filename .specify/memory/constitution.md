<!--
SYNC IMPACT REPORT
==================
Version Change: INITIAL → 1.0.0
Constitution Type: Initial ratification for PHP web application project

Principles Defined:
  1. Clean Architecture (MVC Pattern) - NEW
  2. Single Responsibility Classes - NEW
  3. Minimal Global Code - NEW
  4. Simple, Responsive UX with TailwindCSS Only - NEW
  5. No External JavaScript Frameworks - NEW
  6. No Testing Requirements - NEW (OVERRIDE PRINCIPLE)
  7. Secure Coding Standards - NEW
  8. Environment-Based Configuration - NEW

Added Sections:
  - Core Principles (8 principles)
  - Technology Stack (XAMPP, PHP, MySQL, TailwindCSS)
  - Security Requirements (input sanitization, output escaping, validation)
  - Governance

Templates Requiring Updates:
  ✅ plan-template.md - Constitution Check section aligned
  ✅ spec-template.md - Testing marked as optional, aligned with no-test principle
  ✅ tasks-template.md - Test tasks marked optional, aligned with no-test principle

Follow-up TODOs: None
-->

# SHooad Constitution

## Core Principles

### I. Clean Architecture (MVC Pattern)

All application code MUST follow the Model-View-Controller (MVC) architectural pattern with clear
separation of concerns. Models handle data and business logic, Views manage presentation, and
Controllers coordinate between them. No business logic in views, no presentation logic in models.

**Rationale**: MVC ensures maintainability, testability, and allows independent evolution of each
layer. Separation of concerns prevents tight coupling and makes the codebase easier to understand
and modify.

### II. Single Responsibility Classes

Each class MUST have exactly one reason to change. Classes MUST be focused on a single
responsibility with meaningful, descriptive names that reflect their purpose. Avoid god objects
and multi-purpose utility classes.

**Rationale**: Single responsibility principle (SRP) improves code readability, reduces complexity,
simplifies debugging, and makes classes reusable across different contexts. Meaningful names
serve as documentation and reduce cognitive load.

### III. Minimal Global Code

Global variables, functions, and state MUST be avoided. Use dependency injection, class properties,
and method parameters instead. Configuration and shared resources MUST be encapsulated in
dedicated classes or passed explicitly.

**Rationale**: Global state creates hidden dependencies, makes code unpredictable and difficult to
reason about, and introduces subtle bugs. Explicit dependencies make code flow transparent and
maintainable.

### IV. Simple, Responsive UX with TailwindCSS Only

User interfaces MUST be simple, responsive, and fast-loading. All styling MUST use TailwindCSS
utility classes exclusively. No custom CSS frameworks or preprocessors. Optimize for mobile-first
design with progressive enhancement for larger screens.

**Rationale**: TailwindCSS provides consistent, maintainable styling without bloat. Simple UX
reduces user cognitive load. Fast-loading pages improve user satisfaction and SEO. Mobile-first
ensures accessibility across devices.

### V. No External JavaScript Frameworks (NON-NEGOTIABLE)

External JavaScript frameworks (React, Vue, Angular, jQuery, etc.) are PROHIBITED. Use vanilla
JavaScript for any required client-side interactivity. Keep JavaScript minimal and progressive-
enhancement focused.

**Rationale**: Eliminates dependency overhead, reduces bundle size, ensures fast page loads, and
maintains simplicity. Server-side rendering with PHP provides sufficient functionality for most
use cases. Vanilla JS is sufficient for minor enhancements.

### VI. No Testing Requirements (NON-NEGOTIABLE OVERRIDE)

Unit tests, integration tests, and end-to-end tests are NOT required. This principle overrides
any testing recommendations or requirements from other sections, templates, or documentation.

**Rationale**: This project prioritizes rapid development and deployment over test coverage.
Manual testing and production monitoring are sufficient for the project's risk tolerance and
scale.

### VII. Secure Coding Standards (NON-NEGOTIABLE)

All user input MUST be sanitized before processing. All output MUST be escaped before rendering.
All user data MUST be validated against expected format and constraints. Use prepared statements
for all database queries. Never trust client-side validation alone.

**Rationale**: Security vulnerabilities can lead to data breaches, system compromise, and legal
liability. Input sanitization prevents injection attacks. Output escaping prevents XSS. Validation
ensures data integrity. Prepared statements prevent SQL injection.

### VIII. Environment-Based Configuration

All configuration values, credentials, API keys, and environment-specific settings MUST be stored
in a `.env` file. The `.env` file MUST be excluded from version control. Use a `.env.example`
file with placeholder values as documentation. Load environment variables at application bootstrap.

**Rationale**: Separating configuration from code enables secure credential management, supports
multiple environments (development, staging, production), and prevents accidental exposure of
sensitive data in version control.

## Technology Stack

**Required Technologies** (NON-NEGOTIABLE):
- **Server Environment**: XAMPP (Apache, PHP, MySQL)
- **Backend Language**: PHP (object-oriented, modern versions 7.4+)
- **Database**: MySQL (accessed via PDO with prepared statements)
- **Styling**: TailwindCSS (utility-first CSS framework, CDN or compiled)
- **JavaScript**: Vanilla JavaScript only (progressive enhancement)

**Prohibited Technologies**:
- External JavaScript frameworks (React, Vue, Angular, jQuery, etc.)
- CSS preprocessors other than Tailwind's JIT compiler
- Package managers for front-end dependencies (minimize dependencies)
- ORMs (use PDO directly for transparency and control)
- Testing frameworks and test runners

**Architecture Pattern**: Model-View-Controller (MVC) with clear separation

## Security Requirements

**Mandatory Security Practices**:

1. **Input Sanitization**: Use `filter_input()`, `filter_var()`, and context-appropriate
   sanitization functions on all user input before processing or storage.

2. **Output Escaping**: Use `htmlspecialchars()` with `ENT_QUOTES` and UTF-8 encoding for all
   HTML output. Use `json_encode()` for JSON output.

3. **Data Validation**: Validate all user data against expected types, formats, ranges, and
   constraints. Reject invalid input explicitly. Never assume client-side validation is sufficient.

4. **SQL Injection Prevention**: Use PDO prepared statements with bound parameters for all
   database queries. Never concatenate user input into SQL strings.

5. **Authentication & Authorization**: Implement secure session management with regeneration.
   Verify user permissions before allowing access to resources or actions.

6. **HTTPS**: Use HTTPS in production environments. Configure secure cookie flags
   (HttpOnly, Secure, SameSite).

7. **Error Handling**: Log errors server-side but display generic error messages to users.
   Never expose stack traces, database details, or system information to end users.

## Governance

**Constitution Authority**: This constitution supersedes all other development practices,
templates, and documentation. When conflicts arise, constitution principles take precedence.

**Amendment Process**: Amendments to this constitution require:
1. Documented justification for the change
2. Impact analysis on existing code and practices
3. Version increment following semantic versioning rules
4. Update of all dependent templates and documentation
5. Explicit approval before merging

**Compliance Verification**: All feature specifications, implementation plans, and code reviews
MUST verify alignment with constitutional principles. Violations MUST be justified and documented
in the "Complexity Tracking" section of implementation plans.

**Semantic Versioning**:
- **MAJOR**: Backward-incompatible changes (principle removal, redefinition)
- **MINOR**: New principles added or material expansions
- **PATCH**: Clarifications, wording improvements, non-semantic refinements

**Review Cycle**: Constitution compliance MUST be reviewed during:
- Feature specification creation
- Implementation plan approval
- Code review and merge approval

**Version**: 1.0.0 | **Ratified**: 2025-10-30 | **Last Amended**: 2025-10-30
