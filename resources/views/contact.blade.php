@extends('layouts.app')

@push('css')
<style>
    .contact-hero {
        min-height: 42vh;
        display: flex;
        align-items: center;
        background: linear-gradient(135deg, rgba(239, 79, 95, 0.14), rgba(248, 248, 248, 0.98));
        padding: 4rem 0;
    }

    .contact-hero .page-title {
        font-size: clamp(2rem, 4vw, 3rem);
        font-weight: 800;
        letter-spacing: -0.04em;
        margin-bottom: 1rem;
        color: var(--zomato-dark);
    }

    .contact-hero .page-subtitle {
        color: var(--zomato-grey);
        font-size: 1rem;
        line-height: 1.75;
        max-width: 680px;
    }

    .contact-card {
        border-radius: 1.5rem;
        border: 1px solid rgba(226, 232, 240, 0.85);
        padding: 2.5rem;
        background: #ffffff;
        box-shadow: 0 32px 80px rgba(15, 23, 42, 0.08);
        max-width: 780px;
        margin: 0 auto;
    }

    .contact-card h2 {
        font-size: clamp(1.75rem, 2.5vw, 2.25rem);
        margin-bottom: 1.5rem;
        color: var(--zomato-dark);
    }

    .contact-page {
        width: 100vw;
        overflow-x: hidden;
    }

    .contact-detail {
        padding-left: 0.9rem;
        border-left: 3px solid rgba(239, 79, 95, 0.18);
    }

    .contact-detail + .contact-detail {
        margin-top: 1.8rem;
    }

    .contact-detail strong {
        display: block;
        margin-bottom: 0.6rem;
        color: var(--zomato-dark);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        font-size: 0.95rem;
    }

    .contact-detail p {
        margin: 0;
        color: #5b6470;
        line-height: 1.85;
        font-size: 1rem;
    }

    @media (max-width: 991px) {
        .contact-hero {
            padding: 3rem 0;
            min-height: auto;
        }

        .contact-card {
            padding: 2rem;
        }
    }
</style>
@endpush

@section('content')
<section class="contact-hero contact-page">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row align-items-center gy-4">
            <div class="col-lg-7">
                <p class="text-uppercase fw-bold text-zomato mb-2">Contact Us</p>
                <h1 class="page-title">We’re here to help. Let’s connect.</h1>
                <p class="page-subtitle">Questions about ordering, reservations, or our menu? Send a message or use the details below and we’ll get back to you as soon as possible.</p>
            </div>
        </div>
    </div>
</section>

<div class="container-fluid py-5 px-4 px-lg-5">
    <div class="row gy-4 justify-content-center">
        <div class="col-12 col-md-10 col-lg-8 col-xl-6">
            <div class="contact-card">
                <h2 class="fw-bold mb-4">Get in touch</h2>
                <div class="contact-detail mb-4">
                    <strong>Address</strong>
                    <p>Athwa Gate, Surat, Gujarat, India</p>
                </div>
                <div class="contact-detail mb-4">
                    <strong>Phone</strong>
                    <p>+91 12345 67890</p>
                </div>
                <div class="contact-detail mb-4">
                    <strong>Email</strong>
                    <p>hello@sipnbite.com</p>
                </div>
                <div class="contact-detail">
                    <strong>Opening hours</strong>
                    <p>Daily, 11:00 AM – 11:00 PM</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection