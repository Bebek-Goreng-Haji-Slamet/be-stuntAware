#!/bin/bash

# ============================================================
# STUNTAWARE API - DUMMY DATA & TESTING SETUP
# Quick Command Reference
# ============================================================

echo "StuntAware API - Setup & Testing Commands"
echo "=========================================="
echo ""

# ============================================================
# 1. DATABASE SETUP
# ============================================================

echo "1️⃣  DATABASE SETUP"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Reset database & seed dummy data:"
echo "$ php artisan migrate:fresh --seed"
echo ""

echo "Or seed only (if migrations already exist):"
echo "$ php artisan db:seed --class=DummyDataSeeder"
echo ""

echo "Verify data in database:"
echo "$ php artisan tinker"
echo "  > User::count()          # Check 3 users"
echo "  > Child::all()           # Check 5 children"
echo "  > GrowthRecord::all()    # Check 9 records"
echo "  > exit                   # Exit tinker"
echo ""

# ============================================================
# 2. START API SERVER
# ============================================================

echo "2️⃣  START API SERVER"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Start Laravel development server:"
echo "$ php artisan serve"
echo ""

echo "Server akan berjalan di: http://localhost:8000"
echo "API endpoint: http://localhost:8000/api"
echo ""

# ============================================================
# 3. API TESTING
# ============================================================

echo "3️⃣  API TESTING"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Check API Health:"
echo "$ curl -X GET 'http://localhost:8000/api/health'"
echo ""

echo "Login (get token):"
echo "$ curl -X POST 'http://localhost:8000/api/auth/login' \\"
echo "  -H 'Content-Type: application/json' \\"
echo "  -d '{\"email\":\"siti@example.com\",\"password\":\"password123\"}'"
echo ""

echo "Get all children (replace TOKEN with actual token):"
echo "$ curl -X GET 'http://localhost:8000/api/children' \\"
echo "  -H 'Authorization: Bearer TOKEN'"
echo ""

echo "Get growth records for child 1:"
echo "$ curl -X GET 'http://localhost:8000/api/children/1/growth' \\"
echo "  -H 'Authorization: Bearer TOKEN'"
echo ""

echo "Get nutrition records for child 1:"
echo "$ curl -X GET 'http://localhost:8000/api/children/1/nutrition' \\"
echo "  -H 'Authorization: Bearer TOKEN'"
echo ""

# ============================================================
# 4. POSTMAN TESTING
# ============================================================

echo "4️⃣  POSTMAN TESTING"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Steps:"
echo "1. Open Postman"
echo "2. File > Import"
echo "3. Select: Postman-Collection.json"
echo "4. Click Import"
echo "5. Set environment variables:"
echo "   - base_url = http://localhost:8000/api"
echo "   - token = (get from login response)"
echo "6. Start testing from Health Check"
echo ""

# ============================================================
# 5. VIEW DATA
# ============================================================

echo "5️⃣  VIEW DATA IN DATABASE"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Enter Laravel Tinker:"
echo "$ php artisan tinker"
echo ""

echo "View all users:"
echo "  > User::all()"
echo ""

echo "View specific user with children:"
echo "  > User::find(1)->load('children')"
echo ""

echo "View all children with growth records:"
echo "  > Child::with('growthRecords')->get()"
echo ""

echo "View growth records for child 1:"
echo "  > Child::find(1)->growthRecords"
echo ""

echo "View nutrition records for child 1:"
echo "  > Child::find(1)->nutritionRecords"
echo ""

echo "Check stunting status:"
echo "  > GrowthRecord::where('child_id', 3)->latest()->first()"
echo ""

echo "Exit Tinker:"
echo "  > exit"
echo ""

# ============================================================
# 6. RESET/CLEANUP
# ============================================================

echo "6️⃣  RESET & CLEANUP"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Delete all data from tables:"
echo "$ php artisan tinker"
echo "  > User::truncate()"
echo "  > Child::truncate()"
echo "  > GrowthRecord::truncate()"
echo "  > NutritionRecord::truncate()"
echo "  > exit"
echo ""

echo "Or reset everything (fresh migrate + seed):"
echo "$ php artisan migrate:fresh --seed"
echo ""

echo "Or just fresh without seed:"
echo "$ php artisan migrate:fresh"
echo ""

# ============================================================
# 7. DEBUG & LOGS
# ============================================================

echo "7️⃣  DEBUG & LOGS"
echo "─────────────────────────────────────────────────────"
echo ""

echo "View Laravel logs (real-time):"
echo "$ tail -f storage/logs/laravel.log"
echo ""

echo "View logs in a new terminal:"
echo "$ cat storage/logs/laravel.log"
echo ""

echo "Clear logs:"
echo "$ rm storage/logs/laravel.log"
echo ""

# ============================================================
# 8. TESTING WORKFLOW
# ============================================================

echo "8️⃣  RECOMMENDED TESTING WORKFLOW"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Terminal 1 - Start Server:"
echo "$ cd api/"
echo "$ php artisan serve"
echo ""

echo "Terminal 2 - View Logs:"
echo "$ cd api/"
echo "$ tail -f storage/logs/laravel.log"
echo ""

echo "Terminal 3 - Test API:"
echo "$ # Use Postman or cURL commands"
echo ""

echo "Or use 3 PHP terminals:"
echo "$ # Terminal 1: php artisan serve"
echo "$ # Terminal 2: php artisan tinker"
echo "$ # Terminal 3: curl commands or Postman"
echo ""

# ============================================================
# 9. QUICK REFERENCE - USERS & PASSWORDS
# ============================================================

echo "9️⃣  USER CREDENTIALS"
echo "─────────────────────────────────────────────────────"
echo ""

echo "User 1:"
echo "  Email: siti@example.com"
echo "  Password: password123"
echo "  Children: Amir (at-risk), Nisa (normal)"
echo ""

echo "User 2:"
echo "  Email: ani@example.com"
echo "  Password: password123"
echo "  Children: Rara (stunted), Budi (normal)"
echo ""

echo "User 3:"
echo "  Email: dewi@example.com"
echo "  Password: password123"
echo "  Children: Yuki (normal)"
echo ""

# ============================================================
# 10. TESTING ENDPOINTS SUMMARY
# ============================================================

echo "🔟 KEY TESTING ENDPOINTS"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Health Check:"
echo "  GET /health"
echo ""

echo "Authentication:"
echo "  POST /auth/register"
echo "  POST /auth/login"
echo "  GET /auth/me"
echo "  POST /auth/logout"
echo ""

echo "Children (Protected):"
echo "  GET /children"
echo "  POST /children"
echo "  GET /children/{id}"
echo "  PUT /children/{id}"
echo "  DELETE /children/{id}"
echo ""

echo "Growth Records (Protected):"
echo "  GET /children/{childId}/growth"
echo "  POST /children/{childId}/growth"
echo "  GET /children/{childId}/growth/{recordId}"
echo "  GET /children/{childId}/growth/trend"
echo "  DELETE /children/{childId}/growth/{recordId}"
echo ""

echo "Nutrition Records (Protected):"
echo "  GET /children/{childId}/nutrition"
echo "  POST /children/{childId}/nutrition"
echo "  POST /children/{childId}/nutrition/custom"
echo "  GET /children/{childId}/nutrition/summary"
echo "  GET /children/{childId}/nutrition/needs"
echo "  GET /children/{childId}/nutrition/recommendations"
echo "  DELETE /children/{childId}/nutrition/{recordId}"
echo ""

echo "Foods (Protected):"
echo "  GET /foods/search?q=nasi&limit=10"
echo ""

# ============================================================
# 11. COMMON ISSUES & SOLUTIONS
# ============================================================

echo "🚨 COMMON ISSUES & SOLUTIONS"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Issue: 'Class DummyDataSeeder not found'"
echo "Solution:"
echo "  $ ls database/seeders/DummyDataSeeder.php"
echo "  $ # Make sure file exists in correct location"
echo ""

echo "Issue: 'Foreign key constraint failed'"
echo "Solution:"
echo "  $ php artisan migrate"
echo "  $ php artisan db:seed --class=DummyDataSeeder"
echo ""

echo "Issue: 'Illuminate\\Database\\QueryException'"
echo "Solution:"
echo "  $ php artisan migrate:fresh"
echo "  $ php artisan db:seed --class=DummyDataSeeder"
echo ""

echo "Issue: Port 8000 already in use"
echo "Solution:"
echo "  $ php artisan serve --port=8001"
echo "  $ # or kill the process using port 8000"
echo ""

echo "Issue: Token invalid/expired"
echo "Solution:"
echo "  $ # Login again to get fresh token"
echo "  $ # Update token in Postman variables"
echo ""

# ============================================================
# 12. FILE LOCATIONS
# ============================================================

echo "📁 IMPORTANT FILE LOCATIONS"
echo "─────────────────────────────────────────────────────"
echo ""

echo "Dummy Data Files:"
echo "  - dummy-data.json"
echo "  - dummy-data.sql"
echo "  - database/seeders/DummyDataSeeder.php"
echo ""

echo "Documentation:"
echo "  - 00-INDEX-FILES.md"
echo "  - DUMMY-DATA-README.md"
echo "  - DUMMY-DATA-SUMMARY.md"
echo "  - DATA-VISUALIZATION.md"
echo "  - API-TESTING-GUIDE.md"
echo ""

echo "Postman:"
echo "  - Postman-Collection.json"
echo "  - api-tests.json"
echo ""

echo "Examples:"
echo "  - JSON-CONTOH-REQUEST.js"
echo ""

# ============================================================
echo ""
echo "✅ SETUP COMPLETE!"
echo "═════════════════════════════════════════════════════"
echo ""
echo "Next steps:"
echo "1. Run: php artisan db:seed --class=DummyDataSeeder"
echo "2. Run: php artisan serve"
echo "3. Import Postman-Collection.json to Postman"
echo "4. Start testing!"
echo ""
echo "For detailed guide, see: API-TESTING-GUIDE.md"
echo ""
