<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Manna') }} - Privacy Policy</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; background: linear-gradient(-45deg, #10B981, #059669, #047857, #065f46); background-size: 400% 400%; animation: gradientBG 15s ease infinite; min-height: 100vh; color: #1f2937; -webkit-font-smoothing: antialiased; padding: 2rem; }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container { max-width: 900px; margin: 0 auto; }

        .a4-paper {
            background: #fff;
            padding: 3rem 4rem;
            border-radius: 8px;
            box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
            animation: slideUp 0.8s ease-out;
            min-height: 1000px;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header-section { text-align: center; margin-bottom: 3rem; padding-bottom: 2rem; border-bottom: 2px solid #e5e7eb; }

        .header-section h1 { font-size: 2.5rem; font-weight: 800; color: #111827; margin-bottom: 0.5rem; letter-spacing: -0.02em; }

        .header-section p { color: #6b7280; font-size: 1.1rem; }

        .section { margin-bottom: 2.5rem; animation: fadeIn 0.6s ease-out; animation-fill-mode: both; }

        .section:nth-child(1) { animation-delay: 0.1s; }
        .section:nth-child(2) { animation-delay: 0.2s; }
        .section:nth-child(3) { animation-delay: 0.3s; }
        .section:nth-child(4) { animation-delay: 0.4s; }
        .section:nth-child(5) { animation-delay: 0.5s; }
        .section:nth-child(6) { animation-delay: 0.6s; }
        .section:nth-child(7) { animation-delay: 0.7s; }
        .section:nth-child(8) { animation-delay: 0.8s; }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .section h2 { font-size: 1.5rem; font-weight: 700; color: #111827; margin-bottom: 1rem; letter-spacing: -0.01em; }

        .section h3 { font-size: 1.15rem; font-weight: 600; color: #374151; margin-bottom: 0.75rem; margin-top: 1.5rem; }

        .section p { color: #4b5563; line-height: 1.8; margin-bottom: 1rem; font-size: 0.95rem; }

        .section ul { list-style: none; padding-left: 0; }

        .section ul li { color: #4b5563; line-height: 1.8; margin-bottom: 0.5rem; padding-left: 1.5rem; position: relative; font-size: 0.95rem; }

        .section ul li::before { content: '•'; position: absolute; left: 0; color: #10B981; font-weight: bold; }

        .back-link { display: inline-flex; align-items: center; gap: 0.5rem; color: #fff; text-decoration: none; font-weight: 500; margin-bottom: 2rem; transition: all 0.2s ease; }

        .back-link:hover { transform: translateX(-5px); }

        .back-link svg { width: 20px; height: 20px; }

        .highlight { background: #f0fdf4; padding: 0.25rem 0.5rem; border-radius: 4px; color: #059669; font-weight: 500; }

        .effective-date { background: #f3f4f6; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center; font-weight: 500; color: #374151; }

        @media (max-width: 768px) {
            body { padding: 1rem; }
            .a4-paper { padding: 2rem 1.5rem; }
            .header-section h1 { font-size: 1.75rem; }
            .section h2 { font-size: 1.25rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ url('/') }}" class="back-link">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Home
        </a>

        <div class="a4-paper">
            <div class="header-section">
                <h1>Privacy Policy</h1>
                <p>Last Updated: January 1, 2025</p>
            </div>

            <div class="effective-date">
                Effective Date: January 1, 2025
            </div>

            <div class="section">
                <h2>1. Introduction</h2>
                <p>Welcome to <span class="highlight">{{ config('app.name', 'Manna') }}</span> ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. If you have any questions or concerns about our policy, or our practices with regard to your personal information, please contact us at support@manna.com.</p>
                <p>This privacy policy applies to all information collected by {{ config('app.name', 'Manna') }}, including through our website, mobile applications, and related services (collectively, the "Services"). By using the Services, you agree to the collection and use of information in accordance with this policy.</p>
            </div>

            <div class="section">
                <h2>2. Information We Collect</h2>
                <p>We collect several types of information from and about users of our Services, including information:</p>
                <ul>
                    <li><strong>Personal Identification Information:</strong> Name, email address, phone number, and other contact information you provide when creating an account or using our Services.</li>
                    <li><strong>Account Information:</strong> Username, password, and other authentication credentials. We store this information securely and never share your password with third parties.</li>
                    <li><strong>Property Information:</strong> Details about properties you list, rent, or inquire about, including addresses, photos, descriptions, and rental terms.</li>
                    <li><strong>Payment Information:</strong> Payment method details processed through secure third-party payment processors. We do not store complete credit card numbers on our servers.</li>
                    <li><strong>Usage Information:</strong> Information about how you use our Services, including pages visited, features used, time spent, and other behavioral data.</li>
                    <li><strong>Device Information:</strong> IP address, browser type, operating system, device identifiers, and other technical information about your device.</li>
                    <li><strong>Location Information:</strong> Approximate location based on IP address or precise location if you enable location services on your device.</li>
                </ul>
            </div>

            <div class="section">
                <h2>3. How We Use Your Information</h2>
                <p>We use the information we collect for various purposes, including to:</p>
                <ul>
                    <li>Provide, maintain, and improve our Services</li>
                    <li>Process transactions and send related information</li>
                    <li>Send technical notices and support messages</li>
                    <li>Respond to comments, questions, and customer service requests</li>
                    <li>Send marketing and promotional communications (with your consent)</li>
                    <li>Monitor and analyze trends, usage, and activities</li>
                    <li>Detect, prevent, and address technical issues and fraud</li>
                    <li>Comply with legal obligations and enforce our terms</li>
                    <li>Personalize your experience and deliver relevant content</li>
                    <li>Facilitate communication between landlords and tenants</li>
                    <li>Verify identity and prevent fraudulent activities</li>
                </ul>
            </div>

            <div class="section">
                <h2>4. Information Sharing</h2>
                <p>We may share information we collect with third parties in the following circumstances:</p>
                <h3>4.1 Service Providers</h3>
                <p>We may share your information with third-party service providers who perform services on our behalf, such as payment processing, data hosting, analytics, and customer support. These service providers have access to your information only to perform specific tasks and are contractually obligated to protect your information.</p>
                <h3>4.2 Business Transfers</h3>
                <p>If we are involved in a merger, acquisition, or sale of assets, your information may be transferred as part of the transaction. We will notify you of any such transfer and will continue to protect your information as described in this policy.</p>
                <h3>4.3 Legal Requirements</h3>
                <p>We may disclose your information if required to do so by law or in response to valid requests by public authorities (e.g., a court or government agency). We may also disclose information to protect our rights, property, or safety, or that of our users or others.</p>
                <h3>4.4 Other Users</h3>
                <p>When you use our rental platform, certain information may be shared with other users to facilitate transactions. For example, landlords may see tenant contact information for rental applications, and tenants may see property details and landlord contact information for inquiries.</p>
            </div>

            <div class="section">
                <h2>5. Data Security</h2>
                <p>We implement appropriate technical and organizational measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction. These measures include:</p>
                <ul>
                    <li>Encryption of sensitive data in transit and at rest</li>
                    <li>Secure authentication protocols and password hashing</li>
                    <li>Regular security audits and vulnerability assessments</li>
                    <li>Access controls and authentication for our systems</li>
                    <li>Employee training on data protection practices</li>
                    <li>Secure data storage and backup procedures</li>
                </ul>
                <p>However, no method of transmission over the Internet or electronic storage is completely secure. While we strive to use commercially acceptable means to protect your information, we cannot guarantee its absolute security.</p>
            </div>

            <div class="section">
                <h2>6. Data Retention</h2>
                <p>We retain your personal information for as long as necessary to provide our Services and fulfill the purposes outlined in this policy, unless a longer retention period is required or permitted by law. We may retain certain information after you close your account as required by law or for legitimate business purposes.</p>
                <p>When we no longer need your information, we will securely delete or anonymize it. However, some information may remain in our backup systems for a reasonable period for disaster recovery purposes.</p>
            </div>

            <div class="section">
                <h2>7. Your Rights and Choices</h2>
                <p>Depending on your location, you may have certain rights regarding your personal information, including:</p>
                <ul>
                    <li><strong>Access:</strong> Request access to the personal information we hold about you</li>
                    <li><strong>Correction:</strong> Request correction of inaccurate or incomplete information</li>
                    <li><strong>Deletion:</strong> Request deletion of your personal information</li>
                    <li><strong>Portability:</strong> Request transfer of your information to another service</li>
                    <li><strong>Objection:</strong> Object to processing of your information</li>
                    <li><strong>Restriction:</strong> Request restriction of processing of your information</li>
                    <li><strong>Withdraw Consent:</strong> Withdraw consent for processing where consent is the legal basis</li>
                </ul>
                <p>To exercise these rights, please contact us at support@manna.com. We will respond to your request within 30 days in accordance with applicable law.</p>
            </div>

            <div class="section">
                <h2>8. Children's Privacy</h2>
                <p>Our Services are not intended for children under the age of 18. We do not knowingly collect personal information from children under 18. If you are a parent or guardian and believe your child has provided us with personal information, please contact us, and we will delete such information.</p>
            </div>

            <div class="section">
                <h2>9. International Data Transfers</h2>
                <p>Your information may be transferred to and processed in countries other than your country of residence. When we transfer your information internationally, we ensure appropriate safeguards are in place to protect your information in accordance with this policy and applicable data protection laws.</p>
            </div>

            <div class="section">
                <h2>10. Changes to This Policy</h2>
                <p>We may update this privacy policy from time to time. We will notify you of any material changes by posting the new policy on our website and updating the "Last Updated" date. We encourage you to review this policy periodically to stay informed about how we protect your information.</p>
            </div>

            <div class="section">
                <h2>11. Contact Us</h2>
                <p>If you have any questions about this privacy policy or our data practices, please contact us:</p>
                <ul>
                    <li>Email: support@manna.com</li>
                    <li>Phone: +254 700 000 000</li>
                    <li>Address: Nairobi, Kenya</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>
