# Higher Learning Marketers

Website for [Higher Learning Marketers](https://higherlearningmarketers.com), a U.S. education lead generation company.

## What this site is

Higher Learning Marketers connects two groups:

1. **Universities and colleges** that need qualified student inquiries for nursing, business, and career programs.
2. **Prospective students** looking for accredited programs that fit their goals and schedule.

The site explains HLM's services, partnership options, and how students can start an inquiry. Lead capture forms are consent-based and built for TCPA compliance.

## Main pages

| Page | Purpose |
|------|---------|
| Home | Overview, partner logos, programs, process, testimonials |
| About Us | Company story, values, leadership |
| Services | Lead generation and enrollment marketing services |
| Affiliations | Education partnerships and partner network |
| Blog | Articles for universities and students |
| Contact | Inquiry form and contact details |

## Custom build

WordPress site using the **hlm-theme-kadence** theme and HLM plugins:

- `hlm-lead-capture` - lead forms and CRM handoff
- `hlm-security` - hardening and form protection
- `hlm-seo` - meta tags, schema, sitemaps, internal links

Tracked plugins: `hlm-lead-capture`, `hlm-security`, `hlm-seo`, `slim-seo`, and `redirection`. Other plugins can be installed from wp-admin locally, or uploaded to the server manually.

## Local development

Database runs via Docker from the parent project folder:

```bash
docker compose up -d
cd site
php -S localhost:10001 router.php
```

Configure `wp-config.php` locally before first run. It is not committed or deployed via Git.

## Contact

- **Email:** support@higherlearningmarketers.com
- **Phone:** +1 (718) 655-2573
- **Address:** 138-07 82nd Drive, Briarwood, NY 11435
