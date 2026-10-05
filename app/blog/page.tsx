import type { Metadata } from "next";
import { BlogFrame } from "@/components/BlogFrame";
import { Crumbs } from "@/components/Blocks";
import { buildMetadata } from "@/lib/seo";
import { posts } from "@/lib/site";

export const metadata: Metadata = buildMetadata({
  title: "Blog",
  description:
    "Blog posts on how to take a sleep capsule, Elite Sleeper ingredients, and a sleep supplement built for a clear morning.",
  path: "/blog/",
  keywords: ["sleep supplement", "Elite Sleeper", "sleep capsule", "melatonin valerian"],
});

export default function BlogIndexPage() {
  return (
    <BlogFrame returnTo="/blog/">
      <Crumbs items={[{ href: "/", label: "Home" }, { label: "Blog" }]} />
      <h1>Blog</h1>
      <p>
        These pages are for adults comparing a sleep supplement with the way they actually spend the evening. Each note
        stays on one job: <a href="/blog/nighttime-routine/">how to take a sleep capsule</a>, the{" "}
        <a href="/blog/what-is-in-the-bottle/">Elite Sleeper ingredients</a> on the label, or what a{" "}
        <a href="/blog/clear-morning/">sleep supplement without morning grogginess</a> is built to do. The bottle itself
        is 60 capsules for <a href="/order/">$29.95</a>.
      </p>
      <div className="note-list">
        {posts.map((post) => (
          <article className="note-card" key={post.slug}>
            <img src={post.image} alt={post.imageAlt} />
            <div>
              <p className="meta">{post.date}</p>
              <h2>
                <a href={`/blog/${post.slug}/`}>{post.title}</a>
              </h2>
              <p>{post.description}</p>
              <p>
                <a href={`/blog/${post.slug}/`}>Read this post</a>
              </p>
            </div>
          </article>
        ))}
      </div>
    </BlogFrame>
  );
}
