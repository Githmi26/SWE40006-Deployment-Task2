using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using StudentGradeCalculator.Models;

namespace StudentGradeCalculator.Controllers
{
    public class HomeController : Controller
    {
        private readonly ILogger<HomeController> _logger;

        public HomeController(ILogger<HomeController> logger)
        {
            _logger = logger;
        }

        [HttpGet]
        public IActionResult Index()
        {
            return View();
        }

        [HttpPost]
        public IActionResult Index(StudentGradeModel model)
        {
            if (!ModelState.IsValid)
            {
                return View(model);
            }

            // Calculate the average mark
            model.Average = Math.Round(
                (model.Mark1 + model.Mark2 + model.Mark3) / 3,
                2
            );

            // Determine the grade
            if (model.Average >= 80)
            {
                model.Grade = "HD - High Distinction";
            }
            else if (model.Average >= 70)
            {
                model.Grade = "D - Distinction";
            }
            else if (model.Average >= 60)
            {
                model.Grade = "C - Credit";
            }
            else if (model.Average >= 50)
            {
                model.Grade = "P - Pass";
            }
            else
            {
                model.Grade = "N - Fail";
            }

            // Determine pass or fail
            if (model.Average >= 50)
            {
                model.Result = "PASS";
            }
            else
            {
                model.Result = "FAIL";
            }

            ViewBag.ShowResult = true;

            return View(model);
        }

        public IActionResult Privacy()
        {
            return View();
        }

        [ResponseCache(
            Duration = 0,
            Location = ResponseCacheLocation.None,
            NoStore = true)]
        public IActionResult Error()
        {
            return View(new ErrorViewModel
            {
                RequestId = Activity.Current?.Id ??
                            HttpContext.TraceIdentifier
            });
        }
    }
}