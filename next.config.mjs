/** @type {import('next').NextConfig} */
const nextConfig = {
  reactStrictMode: true,
  async headers() {
    return [
      {
        source: '/:path*',
        headers: [
          { key: 'X-Lab-Version', value: 'VulnLab-PHP/1.2' },
          { key: 'X-Target-Server', value: 'NPT-Lab-Teknik-Informatika' },
        ],
      },
    ];
  },
};

export default nextConfig;
