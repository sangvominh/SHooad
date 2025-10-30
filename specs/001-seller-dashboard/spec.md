# Feature Specification: Seller Dashboard

**Feature Branch**: `001-seller-dashboard`  
**Created**: 2025-10-30  
**Status**: Draft  
**Input**: User description: "dashboard seller - create the Seller Dashboard for the SHooad e-commerce platform. The dashboard gives sellers a clean, intuitive interface to manage their products. Use PHP (OOP) with XAMPP, MySQL, and TailwindCSS. The layout must include: Left Sidebar: navigation menu with key seller functions (e.g., Add Product, Product List, Orders, Account). Right Main Panel: Top Section: compact statistics summary (e.g., total products, active listings, sales, revenue). Below: product management area showing currently active/online products with options to edit, pause, or delete. Keep the UI minimal, responsive, and visually balanced. Prioritize clean code structure (class-based), reusable components, and no external JS frameworks. Data interactions must be secure (prepared statements, XSS protection)."

## Clarifications

### Session 2025-10-30

- Q: When displaying active products on the dashboard, how should the system handle sellers with many products (e.g., 50+ active listings)? → A: Show first 10-20 products with "View All" link to full product list page
- Q: The dashboard displays total revenue, but over what time period should this revenue be calculated? → A: Current month only (resets monthly)
- Q: When a seller clicks "Delete" on a product, what type of confirmation should be shown? → A: Type product name to confirm deletion (extra safety for critical action)
- Q: When showing the first 10-20 active products on the dashboard, in what order should they be sorted? → A: Most recently modified first (last updated at top)
- Q: On mobile devices, how should the left sidebar navigation initially appear when the dashboard loads? → A: Hidden by default with hamburger/toggle button to open sidebar overlay

## User Scenarios & Manual Verification *(mandatory)*

### User Story 1 - View Dashboard Overview (Priority: P1)

A seller logs into their account and immediately sees a comprehensive overview of their business performance and product status. The dashboard provides at-a-glance statistics including total products, active listings, recent orders, and revenue, allowing sellers to quickly assess their store's health without navigating through multiple pages.

**Why this priority**: This is the primary entry point for sellers and provides immediate value. A seller needs to understand their business status instantly upon login. This is the foundation that all other seller functions build upon.

**Manual Verification Steps**: 
1. Navigate to seller dashboard URL while logged in as a seller
2. Observe that the page loads within 2 seconds
3. Verify statistics display correctly in the top section (total products, active listings, sales count, revenue)
4. Verify the left sidebar shows all navigation options
5. Verify the main panel shows active products with action buttons

**Acceptance Scenarios**:

1. **Given** a seller with 10 products (8 active, 2 paused), **When** they access the dashboard, **Then** the statistics show "Total Products: 10" and "Active Listings: 8"
2. **Given** a seller with sales data, **When** they view the dashboard, **Then** revenue is displayed in a clear, formatted currency format
3. **Given** a seller with no products, **When** they access the dashboard, **Then** statistics show zeros and a helpful message prompts them to add their first product
4. **Given** a seller on a mobile device, **When** they access the dashboard, **Then** the layout adjusts responsively with the sidebar hidden by default and accessible via toggle button
5. **Given** a seller with products, **When** the dashboard loads, **Then** only active/online products are displayed in the product management area by default, sorted by most recently modified

---

### User Story 2 - Navigate Seller Functions (Priority: P2)

A seller uses the left sidebar navigation menu to access different management functions such as adding products, viewing product lists, managing orders, and updating their account settings. The navigation is persistent, clearly labeled, and provides visual feedback for the current section.

**Why this priority**: Navigation is essential for accessing all seller features, but the dashboard overview (P1) must exist first. This enables sellers to move between different management tasks efficiently.

**Manual Verification Steps**: 
1. From the dashboard, click each navigation item in the sidebar (Add Product, Product List, Orders, Account)
2. Verify each click navigates to the correct section
3. Verify the active menu item is visually highlighted
4. On mobile, verify the sidebar can be toggled (collapsed/expanded)
5. Verify navigation persists across all seller pages

**Acceptance Scenarios**:

1. **Given** a seller viewing the dashboard, **When** they click "Add Product" in the sidebar, **Then** they navigate to the product creation form
2. **Given** a seller in any section, **When** they click "Product List", **Then** they see a complete list of all their products (active and paused)
3. **Given** a seller viewing their orders, **When** they click "Dashboard" in the sidebar, **Then** they return to the main dashboard overview
4. **Given** a seller on a mobile device, **When** they tap the menu toggle button, **Then** the sidebar slides in as an overlay from the left
5. **Given** a seller navigating between sections, **When** they view the sidebar, **Then** the current section is highlighted with a distinct visual indicator
6. **Given** a seller on mobile with sidebar open, **When** they tap outside the sidebar or select a menu item, **Then** the sidebar closes automatically

---

### User Story 3 - Manage Active Products (Priority: P1)

A seller views their currently active/online products directly from the dashboard and can quickly take actions such as editing product details, pausing a listing, or deleting a product. Each product displays essential information (name, price, status) and provides action buttons for immediate management without navigating away from the dashboard.

**Why this priority**: This is a core value proposition of the dashboard - enabling quick product management. Sellers need to make rapid changes to their active listings, making this equally critical as viewing the overview.

**Manual Verification Steps**: 
1. From the dashboard main panel, locate the product management area
2. Verify active products are displayed with name, price, and status
3. Click "Edit" on a product and verify navigation to edit form
4. Click "Pause" on a product and verify status changes to paused
5. Click "Delete" on a product, confirm the action, and verify product is removed
6. Verify only active products show initially (paused products excluded)

**Acceptance Scenarios**:

1. **Given** a seller with 5 active products, **When** they view the dashboard, **Then** all 5 products are listed in the product management area
2. **Given** a seller viewing their products, **When** they click "Edit" on a product, **Then** they navigate to a pre-filled form with that product's current details
3. **Given** a seller with an active product, **When** they click "Pause", **Then** the product status changes to paused and it's removed from the active products list
4. **Given** a seller clicks "Delete" on a product, **When** they correctly type the product name in the confirmation dialog and confirm, **Then** the product is permanently removed from their inventory
5. **Given** a seller clicks "Delete" on a product, **When** they type an incorrect product name in the confirmation dialog, **Then** deletion is prevented and an error message is shown
6. **Given** a seller with many products, **When** they view the product list, **Then** products are paginated or scrollable to maintain page performance
7. **Given** a seller attempts to delete their last product, **When** they confirm deletion correctly, **Then** the dashboard updates to show zero products with a helpful prompt

---

### User Story 4 - View Sales Performance (Priority: P3)

A seller reviews their sales performance through the statistics displayed on the dashboard, including total sales count and revenue generated. This provides insight into business performance without requiring detailed reports.

**Why this priority**: While valuable for business insight, this is informational rather than action-oriented. Sellers can still manage products effectively without detailed sales metrics initially.

**Manual Verification Steps**: 
1. Complete a test purchase for one of the seller's products
2. Return to seller dashboard and verify sales count increments
3. Verify revenue amount updates to reflect the new sale
4. Test with multiple sales and verify accurate accumulation
5. Verify currency formatting is correct and readable

**Acceptance Scenarios**:

1. **Given** a seller with completed orders, **When** they view the dashboard, **Then** the sales count displays the total number of completed transactions
2. **Given** a seller with revenue, **When** they view the dashboard, **Then** revenue is displayed with proper currency formatting (e.g., $1,234.56)
3. **Given** a new seller with no sales, **When** they view the dashboard, **Then** sales show as 0 and revenue shows as $0.00
4. **Given** a seller refreshes the dashboard, **When** new sales occur, **Then** statistics update to reflect the latest data

---

### Edge Cases

- What happens when a seller has 0 products? Display helpful empty state with call-to-action to add first product
- What happens when product count exceeds dashboard display limit? Show first 10-20 products with "View All" link to navigate to full product list page
- What happens when a seller tries to pause an already paused product? System should handle gracefully (no-op or show already paused status)
- What happens when network request fails during delete/pause action? Show user-friendly error message and allow retry
- What happens if seller has very long product names or prices with many digits? Truncate with ellipsis and show full details on hover or in edit view
- What happens when database connection fails? Display error message indicating temporary unavailability, log error server-side
- What happens if seller attempts SQL injection through product actions? All queries use prepared statements, malicious input is sanitized
- What happens on very small mobile screens (< 320px width)? Dashboard remains functional with minimal but usable layout

## Requirements *(mandatory)*

### Functional Requirements

- **FR-001**: System MUST display a seller dashboard accessible only to authenticated sellers
- **FR-002**: System MUST show a statistics summary including total products count, active listings count, total sales count for current month, and total revenue for current month
- **FR-003**: System MUST display a left sidebar navigation menu with links to: Dashboard, Add Product, Product List, Orders, and Account
- **FR-004**: System MUST display active/online products in the main panel below statistics, showing the first 10-20 most recently modified products (sorted by last modified date descending) with a "View All" link to the full product list page
- **FR-005**: System MUST provide "Edit", "Pause", and "Delete" action buttons for each displayed product
- **FR-006**: System MUST navigate to product edit form when "Edit" button is clicked
- **FR-007**: System MUST change product status to paused when "Pause" button is clicked
- **FR-008**: System MUST permanently remove product when "Delete" button is clicked after confirmation
- **FR-009**: System MUST highlight the active section in the sidebar navigation
- **FR-010**: System MUST load the dashboard page within 2 seconds under normal conditions
- **FR-011**: System MUST display an empty state message when seller has no products
- **FR-012**: System MUST format currency values consistently (e.g., $1,234.56 format)
- **FR-013**: System MUST be fully responsive, adapting layout for mobile, tablet, and desktop screens with sidebar hidden by default on mobile devices (accessible via hamburger toggle button)
- **FR-014**: System MUST sanitize all user inputs and escape all outputs to prevent XSS attacks
- **FR-015**: System MUST use prepared statements for all database queries to prevent SQL injection
- **FR-016**: System MUST require seller authentication/authorization before displaying dashboard
- **FR-017**: System MUST handle database connection errors gracefully with user-friendly messages
- **FR-018**: System MUST show confirmation dialog before deleting a product, requiring the seller to type the product name to confirm deletion
- **FR-019**: System MUST update statistics in real-time when products are added, paused, or deleted
- **FR-020**: System MUST display product information including product name, price, and status

### Key Entities *(include if feature involves data)*

- **Seller**: Represents a user with seller privileges who can manage products and view their business statistics. Attributes include seller ID, name, email, account status, registration date.

- **Product**: Represents an item for sale managed by a seller. Attributes include product ID, seller ID (owner), product name, description, price, status (active/paused/deleted), creation date, last modified date.

- **Order**: Represents a completed transaction. Attributes include order ID, product ID, buyer ID, seller ID, order date, quantity, total amount, order status.

- **Dashboard Statistics**: Aggregated data derived from products and orders. Includes total products count, active listings count, total sales count, total revenue. This is calculated dynamically, not stored.

## Success Criteria *(mandatory)*

### Measurable Outcomes

- **SC-001**: Sellers can access and view their complete dashboard overview within 2 seconds of navigation
- **SC-002**: Sellers can perform product management actions (edit, pause, delete) without leaving the dashboard or with no more than one click to action
- **SC-003**: Dashboard displays accurately on devices ranging from 320px to 1920px width without horizontal scrolling or broken layouts
- **SC-004**: 95% of sellers can locate and use navigation menu items on first attempt without guidance
- **SC-005**: All statistics (product count, active listings, sales, revenue) update within 1 second after product management actions
- **SC-006**: Zero XSS or SQL injection vulnerabilities in dashboard functionality as verified by security review
- **SC-007**: Dashboard supports at least 100 active products displayed without performance degradation
- **SC-008**: Page weight remains under 500KB for initial dashboard load (excluding images)

## Assumptions

- Sellers are already registered and authenticated in the system (authentication system exists)
- Product images are stored and served separately (not primary concern of dashboard layout)
- Payment processing and order management systems exist independently
- Seller accounts are pre-approved and verified (no onboarding workflow required for this feature)
- Database schema for products, sellers, and orders already exists or will be created as part of implementation
- Revenue calculation includes only completed orders for the current calendar month (not pending or cancelled)
- Sales count includes only completed orders for the current calendar month
- Statistics reset at the beginning of each calendar month (1st day of month at 00:00)
- Single currency is used throughout the system (multi-currency not required initially)
- Sellers manage their own products only (no admin/multi-seller view in this feature)
- Basic CRUD operations for products exist or will be implemented alongside dashboard
- Standard XAMPP environment configuration (Apache, PHP 7.4+, MySQL 5.7+)

## Out of Scope

- Detailed analytics and reporting (charts, graphs, trend analysis)
- Order fulfillment and shipping management
- Customer communication and messaging
- Product review and rating management
- Inventory tracking and stock management
- Multi-seller administration panel
- Advanced product filtering and search in dashboard view
- Bulk product operations (bulk edit, bulk delete, bulk pause)
- Product import/export functionality
- Financial reports and tax calculations
- Integration with external e-commerce platforms
- A/B testing of product listings
- Automated product promotion or marketing features
