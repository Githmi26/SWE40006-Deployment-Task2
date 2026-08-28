using System.Diagnostics;
using Microsoft.AspNetCore.Mvc;
using StudentGradeCalculator.Models;

namespace StudentGradeCalculator.Controllers
{
    public class HomeController : Controller
    {
        private readonly ILogger<HomeController> _logger;
        private readonly IConfiguration _configuration;

        public HomeController(
            ILogger<HomeController> logger,
            IConfiguration configuration)
        {
            _logger = logger;
            _configuration = configuration;
        }

        [HttpGet]
        public IActionResult Index()
        {
            ViewBag.EnvironmentName =
                _configuration["GradeSettings:EnvironmentName"];

            return View();
        }

        [HttpPost]
        public IActionResult Index(StudentGradeModel model)
        {
            ViewBag.EnvironmentName =
                _configuration["GradeSettings:EnvironmentName"];

            if (!ModelState.IsValid)
            {
                return View(model);
            }

            // Read grade thresholds from configuration
            double hdThreshold =
                _configuration.GetValue<double>(
                    "GradeSettings:HDThreshold");

            double dThreshold =
                _configuration.GetValue<double>(
                    "GradeSettings:DThreshold");

            double cThreshold =
                _configuration.GetValue<double>(
                    "GradeSettings:CThreshold");

            double passThreshold =
                _configuration.GetValue<double>(
                    "GradeSettings:PassThreshold");

            // Calculate average
            model.Average = Math.Round(
                (model.Mark1 + model.Mark2 + model.Mark3) / 3,
                2);

            // Determine grade using configuration values
            if (model.Average >= hdThreshold)
            {
                model.Grade = "HD - High Distinction";
            }
            else if (model.Average >= dThreshold)
            {
                model.Grade = "D - Distinction";
            }
            else if (model.Average >= cThreshold)
            {
                model.Grade = "C - Credit";
            }
            else if (model.Average >= passThreshold)
            {
                model.Grade = "P - Pass";
            }
            else
            {
                model.Grade = "N - Fail";
            }

            // Determine pass or fail
            model.Result =
                model.Average >= passThreshold
                    ? "PASS"
                    : "FAIL";

            // Write application log
            _logger.LogInformation(
                "Grade calculation completed. Average={Average}, Grade={Grade}",
                model.Average,
                model.Grade);

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
                RequestId =
                    Activity.Current?.Id ??
                    HttpContext.TraceIdentifier
            });
        }
    }
}