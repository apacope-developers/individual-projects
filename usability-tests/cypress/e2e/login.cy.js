describe('Lifeline Login Tests', () => {

  beforeEach(() => {
    cy.visit('http://127.0.0.1:8001/login')
  })

  it('should load the login page', () => {
    cy.contains('Login')
    cy.get('input[name="email"]').should('be.visible')
    cy.get('input[name="password"]').should('be.visible')
  })

  it('should login successfully', () => {
    cy.get('input[name="email"]').type('bahatiandy07@gmail.com')
    cy.get('input[name="password"]').type('12345678')
    cy.get('button[type="submit"]').click()
    cy.url().should('not.include', '/login')
  })

  it('should fail with wrong password', () => {
    cy.get('input[name="email"]').type('bahatiandy07@gmail.com')
    cy.get('input[name="password"]').type('wrongpassword')
    cy.get('button[type="submit"]').click()
    cy.url().should('include', '/login')
  })

})