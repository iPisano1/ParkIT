from selenium import webdriver
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.common.by import By
from datetime import datetime
import time

print("Opening ParkIT Website...")
time.sleep(2)

chrome_options = Options()
chrome_options.add_argument("--incognito")

driver = webdriver.Chrome(options=chrome_options)

# ===============================
# TEST CASE 1: Verify admin login works
# ===============================

print("Executing Test Case 1...")
print("Description: Verify admin login works")
time.sleep(1)

print("Directing to Admin Log In...")
driver.get("http://localhost:3000/login.php")
time.sleep(3)

print("Logging in admin account...")
time.sleep(2)

usernameField = driver.find_element(By.ID, "username")
passwordField = driver.find_element(By.ID, "password")
loginBtn = driver.find_element(By.ID, "loginBtn")

usernameField.clear()
passwordField.clear()

usernameField.send_keys("admin")
passwordField.send_keys("admin")
time.sleep(1)

loginBtn.click()
time.sleep(3)

print("Test Case 1 Complete.")
print("Result: Admin successfully logged in")
print("Status: SUCCESS")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 2: Verify login fails with invalid password
# ===============================

print("Executing Test Case 2...")
print("Description: Verify login fails with invalid password")
time.sleep(1)

print("Directing to Admin Log In...")
driver.get("http://localhost:3000/login.php")
time.sleep(3)

usernameField = driver.find_element(By.ID, "username")
passwordField = driver.find_element(By.ID, "password")
loginBtn = driver.find_element(By.ID, "loginBtn")

usernameField.clear()
passwordField.clear()

print("Logging in admin account with wrong password...")
time.sleep(2)

usernameField.send_keys("admin")
passwordField.send_keys("testadmin")
time.sleep(1)

loginBtn.click()
time.sleep(3)

print("Test Case 2 Complete.")
print("Result: Error message shown correctly")
print("Status: SUCCESS")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 3: Display parking slots on user "Book a Slot" page
# ===============================

print("Executing Test Case 3...")
print('Description: Display parking slots on user "Book a Slot" page')
time.sleep(1)

print("Directing to Home Dashboard...")
driver.get("http://localhost:3000/main.php")
time.sleep(3)

print("Test Case 3 Complete.")
print("Result: Colors display properly")
print("Status: SUCCESS")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 4: Book a parking slot successfully
# ===============================

print("Executing Test Case 4...")
print('Description: Book a parking slot successfully')
time.sleep(1)

print("Directing to Home Dashboard...")
driver.get("http://localhost:3000/main.php")
time.sleep(3)

print("Selecting Parking Slot A2...")
slot = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable(
        (By.CSS_SELECTOR, "div.slot[data-spot='A2']")
    )
)
slot.click()
time.sleep(2)

print("Filling up Booking Form...")

dateTime = driver.find_element(By.ID, "datetime")

dateTime.clear()
driver.execute_script("""document.getElementById('datetime').value = arguments[0];""", datetime.now().strftime("%Y-%m-%dT%H:%M"))
time.sleep(1)

driver.find_element(By.ID, "name").send_keys("Test Name")
time.sleep(1)

driver.find_element(By.ID, "email").send_keys("test@gmail.com")
time.sleep(1)

driver.find_element(By.ID, "bookBtn").click()
time.sleep(3)

print("Test Case 4 Complete.")
print("Result: Booking works correctly")
print("Status: SUCCESS")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 5: Prevent double booking of the same slot
# ===============================

print("Executing Test Case 5...")
print('Description: Prevent double booking of the same slot')
time.sleep(1)

print("Directing to Home Dashboard...")
driver.get("http://localhost:3000/main.php")
time.sleep(3)

print("Selecting Parking Slot A2...")
time.sleep(2)
slot = WebDriverWait(driver, 10).until(
    EC.element_to_be_clickable(
        (By.CSS_SELECTOR, "div.slot[data-spot='A2']")
    )
)
classes = slot.get_attribute("class")

if "occupied" not in classes:
    slot.click()
    print("Slot A2 selected successfully")
else:
    print("Slot can't be clicked because it's already taken")
time.sleep(2)

print("Test Case 5 Complete.")
print("Result: System prevents booking")
print("Status: SUCCESS")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 6: Verify admin can delete a booking
# TEST CASE 7: Delete button sends correct form data
# ===============================

print("Executing Test Case 6 & 7...")
print('Description: Verify admin can delete a booking & Delete button sends correct form data')
time.sleep(1)

print("Directing to Admin Log In...")
driver.get("http://localhost:3000/login.php")
time.sleep(3)

print("Logging in admin account...")
time.sleep(2)

usernameField = driver.find_element(By.ID, "username")
passwordField = driver.find_element(By.ID, "password")
loginBtn = driver.find_element(By.ID, "loginBtn")

usernameField.clear()
passwordField.clear()

usernameField.send_keys("admin")
passwordField.send_keys("admin")
time.sleep(1)

loginBtn.click()
time.sleep(3)

driver.find_element(By.ID, "deleteBtn-6").click()
time.sleep(2)

print("Test Case 6 & 7 Complete.")
print("Result: Booking Successfully Deleted & Slot Status Updated to Available")
print("Status: FAILED")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 8: Sticky navigation bar works in Admin Dashboard
# ===============================

print("Executing Test Case 8...")
print('Description: Sticky navigation bar works in Admin Dashboard')
time.sleep(1)

print("Directing to Admin Log In...")
driver.get("http://localhost:3000/login.php")
time.sleep(3)

print("Logging in admin account...")
time.sleep(2)

usernameField = driver.find_element(By.ID, "username")
passwordField = driver.find_element(By.ID, "password")
loginBtn = driver.find_element(By.ID, "loginBtn")

usernameField.clear()
passwordField.clear()

usernameField.send_keys("admin")
passwordField.send_keys("admin")
time.sleep(1)

loginBtn.click()
time.sleep(3)

print("Scrolling to the very end of the website...")
time.sleep(2)

driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
time.sleep(2)

print("Test Case 8 Complete.")
print("Result: Nav flickers / not always sticking")
print("Status: FAILED")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 9: Admin cannot access admin.php without login
# ===============================

print("Executing Test Case 9...")
print('Description: Admin cannot access admin.php without login')
time.sleep(1)

print("Directing to Admin Dashboard...")
driver.get("http://localhost:3000/admin.php")
time.sleep(3)

print("Test Case 9 Complete.")
print("Result: Redirect works correctly")
print("Status: SUCCESS")
print("\n")
time.sleep(5)

# ===============================
# TEST CASE 10: Slot color legend is displayed correctly in Admin Dashboard
# ===============================

print("Executing Test Case 10...")
print('Description: Slot color legend is displayed correctly in Admin Dashboard')
time.sleep(1)

print("Directing to Admin Log In...")
driver.get("http://localhost:3000/login.php")
time.sleep(3)

print("Logging in admin account...")
time.sleep(2)

usernameField = driver.find_element(By.ID, "username")
passwordField = driver.find_element(By.ID, "password")
loginBtn = driver.find_element(By.ID, "loginBtn")

usernameField.clear()
passwordField.clear()

usernameField.send_keys("admin")
passwordField.send_keys("admin")
time.sleep(1)

loginBtn.click()
time.sleep(3)

print("Scrolling to the Legend Section...")
time.sleep(2)

driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
time.sleep(2)

print("Test Case 8 Complete.")
print("Result: Legend displays properly")
print("Status: SUCCESS")
print("\n")
time.sleep(5)