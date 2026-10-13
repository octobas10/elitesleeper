import type { Metadata } from "next";
import { site } from "./site";

export function buildMetadata(opts: {
  title: string;
  description: string;
  path: string;
  absolute?: boolean;
  noindex?: boolean;
  keywords?: string[];
}): Metadata {
  const url = new URL(opts.path, site.url).toString();
  return {
    title: opts.absolute ? { absolute: opts.title } : opts.title,
    description: opts.description,
    keywords: opts.keywords,
    alternates: { canonical: url },
    robots: opts.noindex ? { index: false, follow: false } : { index: true, follow: true },
    openGraph: {
      title: opts.title,
      description: opts.description,
      url,
      siteName: site.name,
      type: "website",
      locale: "en_US",
      images: [
        {
          url: "/images/product-promo.jpg",
          width: 1024,
          height: 1024,
          alt: "Elite Sleeper, 60 capsules. A calmer nighttime routine.",
        },
      ],
    },
    twitter: {
      card: "summary_large_image",
      title: opts.title,
      description: opts.description,
      images: ["/images/product-promo.jpg"],
    },
  };
}
