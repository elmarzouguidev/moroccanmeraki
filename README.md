# Moroccan Meraki

**You Teach. We Handle Morocco.**

Moroccan Meraki is a Laravel-based platform for creating, managing,
booking, and operating premium **artist-led retreats in Morocco**.

The concept is inspired by **UpTrek**, adapted to Moroccan Meraki's own
business model and Morocco-specific operations. Rather than functioning
as a traditional tour website, Moroccan Meraki combines a premium
retreat discovery experience with a complete operational platform for
instructors, guests, bookings, accommodation, payments, travel
information, and retreat management.

> The retreat changes. The engine stays.

------------------------------------------------------------------------

## About the Project

Moroccan Meraki works with artists and art instructors who bring their
students and communities to Morocco.

The instructor focuses on:

-   Teaching
-   Their students
-   Their artistic program
-   Promoting the retreat to their audience

Moroccan Meraki handles the Moroccan side:

-   Accommodation, riads, and hotels
-   Private transportation and professional drivers
-   Airport transfers
-   Meals
-   Local guides
-   Cultural experiences
-   Retreat itineraries and scheduling
-   Local logistics and coordination
-   Pre-retreat communication
-   On-the-ground support

The objective is to provide guests with a premium artist-led experience
while giving Moroccan Meraki the infrastructure required to operate
multiple retreats efficiently.

------------------------------------------------------------------------

## Core Philosophy

Moroccan Meraki is not designed as a collection of static tour pages.

Each retreat is structured data connected to the same reusable
application engine:

``` text
Instructor + Retreat
        ↓
Public Retreat Experience
        ↓
Booking
        ↓
Participants
        ↓
Pricing + Availability
        ↓
Payment
        ↓
Customer Portal
        ↓
Travel Information
        ↓
Operations
        ↓
Retreat
```

A retreat may have different instructors, dates, destinations, duration,
prices, capacity, accommodation, room inventory, itinerary, and booking
rules without requiring the application itself to be rebuilt.

------------------------------------------------------------------------

## Technology

Moroccan Meraki is built as a **single Laravel monolith** containing the
public website, booking experience, customer portal, and administration
system.

### Stack

-   **Laravel**
-   **PHP**
-   **Tailwind CSS v4**
-   **MySQL**
-   **Stripe**
-   Laravel queues and scheduled jobs

The application deliberately keeps the public website and operational
system together so that retreat pages, pricing, availability, bookings,
and administration operate on the same domain data.

------------------------------------------------------------------------

## Main Domains

### Retreats

A retreat contains structured information such as:

-   Instructor
-   Title and subtitle
-   Start and end dates
-   Duration
-   Destinations
-   Booking status
-   Minimum and maximum participants
-   Accommodation options
-   Room inventory
-   Pricing
-   Deposit requirements
-   Booking opening rules
-   Balance due rules
-   Itinerary
-   Gallery
-   Accommodation
-   Food experience
-   Inclusions and exclusions
-   FAQs
-   Testimonials

Retreat data drives both the public presentation and booking system.

### Instructors

Instructors are reusable entities rather than content duplicated inside
individual retreats.

An instructor can contain:

-   Name
-   Portrait
-   Biography
-   Professional background
-   Teaching approach
-   Website
-   Social links
-   Artwork and media

The same instructor can therefore lead multiple Moroccan Meraki
retreats.

### Participants

A booking may contain multiple participants.

Two primary participant categories are currently supported by the
business model:

-   **Artist / Participant** --- participates in the instructor's
    artistic program.
-   **Non-Artist Partner** --- participates in the Moroccan travel
    experience without joining the instructor's workshops.

Each participant can have independent pricing, accommodation, payment,
and travel information while remaining part of one overall booking.

### Pricing

Pricing is treated as a reusable domain concept rather than being
hard-coded into retreat columns.

The application is designed around a dedicated `Price` model and
reusable pricing relationships, allowing prices to evolve independently
from the models they belong to.

Monetary values should be stored using integer minor units rather than
floating-point values.

The system also distinguishes between:

-   Published price
-   Price adjustments
-   Final agreed price
-   Amount paid
-   Remaining balance

A historical booking must preserve the price agreed with the customer
even if the currently published retreat price changes later.

### Accommodation & Inventory

Participant capacity and accommodation inventory are separate concepts.

For example, a retreat may support 14 participants while only offering 5
single rooms.

Availability therefore needs to account for:

-   Maximum retreat capacity
-   Paid participants
-   Temporary holds
-   Accommodation selection
-   Single-room inventory
-   Released/cancelled places

Moroccan Meraki does **not** automatically match strangers into shared
rooms.

### Booking

The booking flow is designed to remain simple for customers while
enforcing the underlying business rules.

Conceptually:

``` text
Choose travellers
        ↓
Choose participant type
        ↓
Choose accommodation
        ↓
Calculate price
        ↓
Review booking
        ↓
Enter guest information
        ↓
Accept terms
        ↓
Choose payment method
        ↓
Pay deposit
        ↓
Confirmation
```

A single booking can contain multiple participants with different
participant types and accommodation selections.

------------------------------------------------------------------------

## Capacity & Retreat Status

The initial business model uses:

-   **Minimum:** 6 paid participants
-   **Maximum:** 14 paid participants

Only paying guests count toward participant capacity. Instructors,
Moroccan Meraki staff, and drivers do not.

Retreat states can include:

-   Coming Soon
-   Open for Registration
-   Filling Now
-   Confirmed
-   Full / Waitlist
-   Completed
-   Postponed
-   Cancelled

Some state changes can be derived from application data, but important
business decisions remain under administrator control.

Reaching the minimum threshold does not automatically force a business
decision.

------------------------------------------------------------------------

## Payments

Supported payment methods are planned around:

-   **Stripe**
-   **Bank Transfer**
-   **Wise**

### Stripe

Successful Stripe payments can immediately update the relevant payment
and booking state.

Card details are handled by Stripe and are not stored by Moroccan
Meraki.

### Bank Transfer & Wise

Offline payments initially remain pending.

They do not count as paid capacity until an administrator verifies that
the payment has been received.

### Deposits

Deposits are configured per retreat and are charged **per participant**,
rather than necessarily being a percentage of the booking value.

### Balance

The remaining balance is calculated from the agreed booking value and
payments received.

The current business rule targets the final balance being due **90 days
before departure**.

------------------------------------------------------------------------

## Temporary Holds

Places may be temporarily held while a guest completes payment or
arranges an offline transfer.

The current business rule allows a default **48-hour hold**.

A held place:

-   Temporarily consumes availability
-   Is not considered a paid participant
-   Does not count toward the retreat confirmation threshold
-   Expires automatically if payment is not completed
-   Can be manually extended by an administrator

------------------------------------------------------------------------

## Waitlist

When a retreat reaches capacity, the normal booking action can be
replaced with a waitlist.

Waitlist information may include:

-   Name
-   Email
-   Phone
-   Participant type
-   Room preference
-   Number of people
-   Notes

A newly available place is **not automatically assigned** to the first
person in the waitlist. Moroccan Meraki retains control over whom to
contact.

------------------------------------------------------------------------

## My Retreat

After booking, customers have access to a dedicated **My Retreat** area.

The portal is intended to become the central location for everything
related to their retreat.

Customers can access information such as:

-   Retreat
-   Instructor
-   Dates
-   Destination
-   Participants
-   Accommodation
-   Booking status
-   Total price
-   Deposit paid
-   Outstanding balance
-   Balance due date
-   Payment history
-   Travel information status

### Documents

The portal can also provide:

-   Booking confirmations
-   Payment receipts
-   Itineraries
-   Final trip information
-   Packing information
-   Retreat documents

Customers with an outstanding balance can return to My Retreat to
complete payment.

------------------------------------------------------------------------

## Travel Information

Detailed flight and travel information is intentionally collected
**after booking**, rather than making the initial booking form
unnecessarily large.

Travel information can include:

### Arrival

-   Date
-   Airport
-   Airline
-   Flight number
-   Arrival time

### Departure

-   Date
-   Airport
-   Airline
-   Flight number
-   Departure time

### Additional Information

-   Extra nights before the retreat
-   Extra nights after the retreat
-   Dietary requirements
-   Emergency contact
-   Notes

The current workflow targets:

-   Travel information request around **45 days before departure**
-   Reminder around **14 days before departure**

------------------------------------------------------------------------

## Cancellations & Refunds

Cancellation requests do not automatically trigger refunds.

An administrator reviews:

-   Amount paid
-   Cancellation date
-   Applicable cancellation policy
-   Refund amount
-   Transfer fees
-   Refund status

Internal refund states can include:

-   Refund Required
-   Refund Processing
-   Refunded

This intentionally keeps important financial and business decisions
under human control.

------------------------------------------------------------------------

## Administration

The administration system provides an operational view of each retreat.

Administrators need visibility into:

-   Participants
-   Maximum capacity
-   Remaining places
-   Single rooms remaining
-   Deposits received
-   Outstanding balances
-   Unpaid bookings
-   Temporary holds
-   Waitlist
-   Travel information completion
-   Cancellations
-   Refunds
-   Total booked value
-   Stripe payments
-   Bank/Wise payments

Administrators can also create bookings manually for customers
originating from channels such as email, Instagram, referrals,
instructors, phone, or personal contact.

------------------------------------------------------------------------

## Booking Attribution

Bookings can track their source, including:

-   Website
-   Instagram
-   Email
-   Referral
-   Instructor
-   Personal Contact
-   Other

Instructors may have dedicated/private booking links so bookings
generated through their promotion can be attributed appropriately.

Internal instructor compensation, commissions, supplier costs, margins,
and profit information must never be exposed to customers.

------------------------------------------------------------------------

## Automation

Laravel queues, events, listeners, notifications, and scheduled jobs can
support repetitive operational workflows.

Examples include:

-   Booking confirmation
-   Payment confirmation
-   Retreat confirmation
-   Balance reminders
-   Balance due notifications
-   Travel information requests
-   Travel information reminders
-   Final trip information
-   Pre-arrival reminders
-   Temporary hold expiration
-   Booking opening

Automation assists operations without taking important business
decisions away from Moroccan Meraki administrators.

------------------------------------------------------------------------

## Public Experience

The public retreat experience should immediately answer:

-   What is this retreat?
-   Who is teaching?
-   Where are we going?
-   What will guests experience?
-   What is included?
-   What does it cost?
-   Are places available?
-   How does booking work?
-   What happens after booking?

The visual direction is:

-   Premium
-   Editorial
-   Artistic
-   Sophisticated
-   Warm
-   Moroccan
-   Contemporary
-   Image-led
-   Spacious

The project intentionally avoids the appearance of a generic tourism
website or cheap booking engine.

------------------------------------------------------------------------

## First Retreats

The architecture is designed to support multiple artist-led retreats.

Examples include:

-   **Painting the Blue Pearl & Beyond** --- A Northern Morocco Art
    Retreat with Leslie Lambert
-   **Light & Atmosphere** --- A Watercolor Journey Through Northern
    Morocco with Tatsiana Harbacheuskaya

These retreats are instances of the platform rather than one-off
hard-coded pages.

------------------------------------------------------------------------

## Development Principles

The project follows several important engineering principles:

-   One Laravel monolith
-   Business logic before page-specific implementation
-   Structured retreat data instead of hard-coded page values
-   Reusable models and relationships where concepts repeat
-   Polymorphic relationships where they provide meaningful reuse
-   Integer-based monetary storage
-   Transactional booking and inventory operations
-   Preserve historical booking prices
-   Keep pricing separate from availability
-   Keep participant capacity separate from room inventory
-   Keep payment state separate from booking value
-   Use queues for asynchronous workflows
-   Keep critical business decisions administratively controllable
-   Design every retreat feature with reuse in mind

------------------------------------------------------------------------

## Project Status

Moroccan Meraki is currently being redesigned and rebuilt around its
dedicated retreat-platform architecture.

The existing Moroccan Meraki website and retreat content provide the
foundation for the new Laravel application, while the new system is
being designed to support future instructors and retreats without
rebuilding the underlying engine.

------------------------------------------------------------------------

## Inspiration

Moroccan Meraki is inspired by the instructor-led retreat model of
**UpTrek**, while being adapted specifically for Morocco and Moroccan
Meraki's own operational requirements.

The goal is not simply to reproduce another platform's implementation,
but to build a dedicated retreat infrastructure around Moroccan Meraki's
business:

> **You Teach. We Handle Morocco.**