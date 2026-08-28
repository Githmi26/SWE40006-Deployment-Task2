using System.ComponentModel.DataAnnotations;

namespace StudentGradeCalculator.Models
{
    public class StudentGradeModel
    {
        [Required]
        [Display(Name = "Student Name")]
        public string StudentName { get; set; } = string.Empty;

        [Required]
        [Range(0, 100)]
        [Display(Name = "Subject 1 Mark")]
        public double Mark1 { get; set; }

        [Required]
        [Range(0, 100)]
        [Display(Name = "Subject 2 Mark")]
        public double Mark2 { get; set; }

        [Required]
        [Range(0, 100)]
        [Display(Name = "Subject 3 Mark")]
        public double Mark3 { get; set; }

        public double Average { get; set; }

        public string Grade { get; set; } = string.Empty;

        public string Result { get; set; } = string.Empty;
    }
}