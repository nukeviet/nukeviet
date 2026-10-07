const mapConfig = {
  inline: false,
  annotation: true,
  sourcesContent: true
}

export default context => {
  return {
    // Không sinh map cho CSS trình cài đặt
    map: context.file.dirname.includes('examples') || /[\\/]install[\\/]css$/.test(context.file.dirname) ? false : mapConfig,
    plugins: {
      autoprefixer: {
        cascade: false
      },
      rtlcss: context.env === 'RTL'
    }
  }
}
