class Identity {
  constructor(user) {
    if (!user) {
      this.isGuest = true
      return;
    }
    this.isGuest = false
    this.user = user
  }
  deauth() {
    this.isGuest = true
    this.user = {}
  }
}

export default Identity
