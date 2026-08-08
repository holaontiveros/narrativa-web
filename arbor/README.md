# Arbor 🌳

Welcome to **Arbor**! 🎉

Arbor is a mini-version of the [Roots Trellis](https://roots.io/trellis/) project, focusing solely on the deployment part for a server based on [CloudPanel](https://www.cloudpanel.io/). 🚀

<!-- TODO -->

- Add elementor config on composer
- ~~Add after deployment scripts for theme installs and builds~~
- Integrate uploads sync script
- Change vhost template for cloud panel to prevent wordpress error with bedrock paths
- Add scripts to configure site on cloud panel
  <!-- - create site -->
  - add ssh key
  - set root folder to new `current/web`
  <!-- - allow php reload without sudo `vwcamionespeninsular ALL=(root) NOPASSWD: /usr/sbin/service php8.2-fpm *` -->
  - ~~add after template hooks~~

## Features ✨

- **Simplified Deployment**: Streamlined process for deploying your WordPress site.
- **CloudPanel Integration**: Optimized for servers managed by CloudPanel.
- **Lightweight**: Minimal setup, focusing only on what you need for deployment.

## Getting Started 🛠️

1. **Clone the Repository**:

   ```bash
   git clone https://github.com/yourusername/arbor.git
   cd arbor
   ```

2. **Configure Your Environment**:

   - Update the `.env` file with your server and site details.

3. **Deploy**:
   ```bash
   make deploy SITE=duraplast.mx ENV=production
   ```

## Shared storage migration

Production stores each site's persistent deployment data under its own folder
on the mounted volume. For Duraplast, the layout is:

```text
/home/duraplast/htdocs/duraplast.mx/shared
  -> /mnt/volume_nyc3_01/duraplast.mx/shared
```

Before the first deployment with this configuration, migrate the existing
`shared` directory:

```bash
make migrate-shared SITE=duraplast.mx ENV=production
```

The migration verifies that `/mnt/volume_nyc3_01` is mounted and the destination
is empty, copies the data with `rsync`, performs a final sync during a short
cutover, verifies the result, creates the symlink, and only then removes the old
copy to free disk space. It is safe to run again after a successful migration.

## Requirements 📋

- A server managed by CloudPanel.
- Basic knowledge of SSH and server management.

## Contributing 🤝

We welcome contributions! Please fork the repository and submit a pull request. For major changes, please open an issue first to discuss what you would like to change.

## License 📄

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgements 🙏

- [Roots Trellis](https://roots.io/trellis/) for the inspiration.
- [CloudPanel](https://www.cloudpanel.io/) for their awesome server management tool.

Happy Deploying! 🚀
