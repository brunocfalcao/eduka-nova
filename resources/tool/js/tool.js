Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'eduka-nova',
      path: '/eduka-nova',
      component: require('./components/Tool'),
    },
  ])
})
