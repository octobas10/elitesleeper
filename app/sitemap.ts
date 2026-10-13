import type { MetadataRoute } from "next";
import { posts, site } from "@/lib/site";

export const dynamic = "force-static";

export default function sitemap(): MetadataRoute.Sitemap {
  const paths = [
    "/",
    "/order/",
    "/contact/",
    "/opt-out/",
    "/cancel-plan/",
    "/shipping/",
    "/refund-policy/",
    "/privacy/",
    "/terms/",
    "/blog/",
    ...posts.map((post) => `/blog/${post.slug}/`),
  ];

  return paths.map((path) => ({
    url: new URL(path, site.url).toString(),
    lastModified: new Date("2026-10-02"),
    changeFrequency: path === "/" || path === "/order/" ? "weekly" : "monthly",
    priority: path === "/" ? 1 : path === "/order/" ? 0.9 : 0.7,
  }));
}
