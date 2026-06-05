<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - {{ config('app.name', 'Manna') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            min-height: 100vh;
        }

        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            z-index: 20;
            transition: all 0.3s ease;
        }

        .header:hover {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0.625rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .nav-brand-icon {
            width: 36px;
            height: 36px;
            background: #10B981;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-brand-icon svg {
            width: 20px;
            height: 20px;
            fill: #fff;
        }

        .nav-brand-name {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
        }

        .nav-back {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #f3f4f6;
            border-radius: 8px;
            text-decoration: none;
            color: #374151;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .nav-back:hover {
            background: #e5e7eb;
            color: #10B981;
        }

        .nav-back svg {
            width: 20px;
            height: 20px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            padding: 8rem 2rem 4rem;
        }

        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease-out;
        }

        .page-title {
            font-size: 3rem;
            font-weight: 700;
            color: #111827;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .page-subtitle {
            font-size: 1.25rem;
            color: #6b7280;
            max-width: 600px;
            margin: 0 auto;
        }

        .faq-container {
            animation: fadeInUp 0.8s ease-out 0.2s both;
        }

        .faq-item {
            background: #fff;
            border-radius: 12px;
            margin-bottom: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .faq-item:hover {
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-weight: 600;
            color: #111827;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .faq-question:hover {
            color: #10B981;
        }

        .faq-question svg {
            width: 20px;
            height: 20px;
            color: #6b7280;
            transition: transform 0.3s ease;
        }

        .faq-item.active .faq-question svg {
            transform: rotate(180deg);
            color: #10B981;
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease, padding 0.3s ease;
            padding: 0 1.5rem;
            color: #6b7280;
            line-height: 1.6;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            padding: 0 1.5rem 1.5rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .container {
                padding: 7rem 1.5rem 3rem;
            }

            .faq-question {
                font-size: 1rem;
                padding: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <nav class="header">
        <div class="nav-container">
            <a href="{{ url('/') }}" class="nav-brand">
                <div class="nav-brand-icon">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7L12 12L22 7L12 2Z"/>
                        <path d="M2 17L12 22L22 17" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                        <path d="M2 12L12 17L22 12" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/>
                    </svg>
                </div>
                <span class="nav-brand-name">{{ config('app.name', 'Manna') }}</span>
            </a>
            <a href="{{ url('/') }}" class="nav-back">
                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Home
            </a>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1 class="page-title">Frequently Asked Questions</h1>
            <p class="page-subtitle">Find answers to common questions about Manna</p>
        </div>

        <div class="faq-container">
            <div class="faq-item">
                <div class="faq-question">
                    What is Manna?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    Manna is a modern long-term rental platform connecting tenants with landlords across Africa. We make finding and renting properties simple, secure, and transparent.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    How do I search for properties?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    You can search for properties using our advanced filters including location, price range, number of bedrooms, amenities, and more. Simply enter your preferences and browse through available listings.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    Is Manna free to use?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    Yes! Manna is completely free for tenants. You can browse properties, contact landlords, and schedule viewings at no cost. Landlords pay a small fee to list their properties.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    How do I contact a landlord?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    Once you find a property you're interested in, you can send a message directly to the landlord through our secure messaging system. You can also request a viewing or ask questions about the property.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    Are the properties verified?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    We verify all landlords and properties listed on our platform to ensure authenticity. Our team conducts background checks and property verifications to maintain a safe and trustworthy marketplace.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    How do I make payments?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    Manna offers secure online payment options including mobile money, bank transfers, and credit cards. All payments are processed through our secure payment system for your protection.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    Can I list my property on Manna?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    Absolutely! If you're a landlord or property manager, you can list your properties on Manna. Simply create an account, add your property details, and start reaching thousands of potential tenants.
                </div>
            </div>

            <div class="faq-item">
                <div class="faq-question">
                    What if I have issues with my rental?
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
                <div class="faq-answer">
                    Our support team is here to help. If you encounter any issues with your rental, you can contact us through our support portal, and we'll work to resolve the situation promptly.
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-item');

            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question');
                question.addEventListener('click', function() {
                    const isActive = item.classList.contains('active');
                    
                    // Close all other items
                    faqItems.forEach(otherItem => {
                        otherItem.classList.remove('active');
                    });

                    // Toggle current item
                    if (!isActive) {
                        item.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
