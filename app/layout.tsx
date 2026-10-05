import type { Metadata } from "next";
import type { ReactNode } from "react";
import { Newsreader, Source_Sans_3 } from "next/font/google";
import { Footer } from "@/components/Footer";
import { Header } from "@/components/Header";
import { JsonLd } from "@/components/JsonLd";
import { site } from "@/lib/site";
import "./globals.css";

const sans = Source_Sans_3({
  subsets: ["latin"],
  weight: ["400", "500", "600", "700"],
  style: ["normal", "italic"],
  variable: "--font-sans",
  display: "swap",
});

const serif = Newsreader({
  subsets: ["latin"],
  weight: ["500", "600", "700"],
  style: ["normal", "italic"],
  variable: "--font-serif",
  display: "swap",
});

export const metadata: Metadata = {
  metadataBase: new URL(site.url),
  title: {
    default: "Elite Sleeper | Sleep Capsules for a Clear Morning",
    template: "%s | Elite Sleeper",
  },
  description:
    "Elite Sleeper is a 60-capsule sleep formula with melatonin, valerian root, GABA, and calming herbs. $29.95. Made for a full night and a clear morning.",
  applicationName: site.name,
  authors: [{ name: site.name, url: site.url }],
  creator: site.name,
  category: "Dietary supplement",
  icons: { icon: "/images/logo.png" },
  formatDetection: { telephone: false, email: false, address: false },
};

const orgSchema = {
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "Organization",
      name: site.name,
      url: site.url,
      slogan: site.tagline,
      email: site.email,
      logo: `${site.url}/images/logo.png`,
      address: {
        "@type": "PostalAddress",
        streetAddress: site.street,
        addressLocality: site.city,
        addressRegion: site.region,
        postalCode: site.postal,
        addressCountry: "US",
      },
    },
    {
      "@type": "WebSite",
      name: site.name,
      url: site.url,
      description: site.tagline,
    },
  ],
};

export default function RootLayout({ children }: { children: ReactNode }) {
  return (
    <html lang="en" className={`${sans.variable} ${serif.variable}`}>
      <body>
        <a className="skip" href="#main">
          Skip to content
        </a>
        <Header />
        {children}
        <Footer />
        <JsonLd data={orgSchema} />
      </body>
    </html>
  );
}
